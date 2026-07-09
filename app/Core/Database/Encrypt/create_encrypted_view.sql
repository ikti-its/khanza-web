DO
$$
DECLARE
    tbl RECORD;
    col RECORD;
    decrypt_select TEXT;
    pk_columns TEXT[];
    update_pk_conditions TEXT;
    delete_pk_conditions TEXT;
    encryption_key TEXT := 'BismillahSidangNilaiA';
    plain_name TEXT;
    encrypted_insert_columns TEXT;
    encrypted_insert_values TEXT;
    encrypted_update_assignments TEXT;
        structure_col_type TEXT;

    identity_pk_col TEXT;
    encrypted_insert_columns_noid TEXT;
    encrypted_insert_values_noid TEXT;
    values_clause_noid TEXT;
    insert_clause TEXT;
BEGIN
    FOR tbl IN
        SELECT table_schema, table_name
        FROM information_schema.tables
        WHERE table_type = 'BASE TABLE' --only include tables
            AND table_schema NOT IN ('pg_catalog', 'information_schema') -- exclude system tables
            AND table_name LIKE '%_encrypted'-- include _encrypted only
    LOOP    
        -- Find the primary keys in each table
        SELECT ARRAY_AGG(kcu.column_name)
        INTO pk_columns
        FROM information_schema.table_constraints tc
            JOIN information_schema.key_column_usage kcu
            ON tc.constraint_name = kcu.constraint_name
            AND tc.table_schema = kcu.table_schema
            AND tc.table_name = kcu.table_name
        WHERE tc.table_schema = tbl.table_schema
            AND tc.table_name = tbl.table_name
            AND tc.constraint_type = 'PRIMARY KEY';

        -- If the (single-column) PK is an identity/auto-increment column,
        -- the INSERT trigger needs a separate code path for it: the app
        -- normally omits it and expects Postgres to generate the value.
        identity_pk_col := NULL;
        IF pk_columns IS NOT NULL AND array_length(pk_columns, 1) = 1 THEN
            SELECT column_name INTO identity_pk_col
            FROM information_schema.columns
            WHERE table_schema = tbl.table_schema
                AND table_name = REPLACE(tbl.table_name, '_encrypted', '_structure')
                AND column_name = pk_columns[1]
                AND is_identity = 'YES';
        END IF;

        decrypt_select := '';
        encrypted_insert_columns := '';
        encrypted_insert_values := '';
        encrypted_insert_columns_noid := '';
        encrypted_insert_values_noid := '';
        update_pk_conditions := '';
        delete_pk_conditions := '';
        encrypted_update_assignments := '';

        FOR col IN
            SELECT column_name, data_type, udt_name
            FROM information_schema.columns
            WHERE table_schema = tbl.table_schema AND table_name = tbl.table_name
            ORDER BY ordinal_position
        LOOP
            IF pk_columns IS NOT NULL AND col.column_name = ANY(pk_columns) THEN
                -- Handle SELECTs
                decrypt_select := decrypt_select || 
                    format('%I, ', col.column_name
                );
                -- Handle INSERTs
                encrypted_insert_columns := encrypted_insert_columns ||
                    format('%I, ', col.column_name
                );
                encrypted_insert_values := encrypted_insert_values ||
                    format('NEW.%I, ', col.column_name
                );
                -- Same as above, but skipping the identity PK column (used by
                -- the auto-generate INSERT branch, see identity_pk_col below).
                IF col.column_name IS DISTINCT FROM identity_pk_col THEN
                    encrypted_insert_columns_noid := encrypted_insert_columns_noid || format('%I, ', col.column_name);
                    encrypted_insert_values_noid := encrypted_insert_values_noid || format('NEW.%I, ', col.column_name);
                END IF;
                -- Handle UPDATEs
                update_pk_conditions := update_pk_conditions ||
                    format('%I = NEW.%I AND ', col.column_name, col.column_name
                );
                -- An identity column can't appear in an UPDATE's SET list at
                -- all (not even set to its own value) — Postgres rejects it
                -- outright since there's no OVERRIDING clause for UPDATE like
                -- there is for INSERT. Its value never changes anyway.
                IF col.column_name IS DISTINCT FROM identity_pk_col THEN
                    encrypted_update_assignments := encrypted_update_assignments ||
                        format('%I = NEW.%I, ', col.column_name, col.column_name
                    );
                END IF;
                -- Handle DELETEs
                delete_pk_conditions := delete_pk_conditions || 
                    format('%I = OLD.%I AND ', col.column_name, col.column_name
                );
            ELSE
                -- Find the matching type from _structure
                -- (schema-qualify USER-DEFINED types: the app's DB connection
                -- resets search_path to just "public", so a bare enum/composite
                -- type name defined in another schema wouldn't resolve)
                SELECT
                    CASE
                        WHEN data_type = 'USER-DEFINED' THEN format('%I.%I', udt_schema, udt_name)
                        ELSE data_type
                    END
                INTO structure_col_type
                FROM information_schema.columns
                WHERE table_schema = tbl.table_schema
                    AND table_name = REPLACE(tbl.table_name, '_encrypted', '_structure')
                    AND column_name = col.column_name;

                IF structure_col_type IS NULL THEN
                    RAISE NOTICE 'No matching type found in structure for column %.%', tbl.table_name, col.column_name;
                    structure_col_type := 'text'; -- fallback to text
                END IF;

                -- Handle SELECTs
                decrypt_select := decrypt_select || format(
                    'convert_from(pgp_sym_decrypt(%I, ''%s'')::bytea, ''UTF-8'')::%s AS %I, ',
                    col.column_name, encryption_key, structure_col_type, col.column_name
                );

                -- Handle INSERTs
                encrypted_insert_columns := encrypted_insert_columns || format('%I, ', col.column_name);
                encrypted_insert_values := encrypted_insert_values || format(
                    'pgp_sym_encrypt(NEW.%I::text, %L::text), ', col.column_name, encryption_key
                );
                -- Non-PK columns are never the identity column, so they
                -- always belong in the noid variant too.
                encrypted_insert_columns_noid := encrypted_insert_columns_noid || format('%I, ', col.column_name);
                encrypted_insert_values_noid := encrypted_insert_values_noid || format(
                    'pgp_sym_encrypt(NEW.%I::text, %L::text), ', col.column_name, encryption_key
                );

                -- Handle UPDATEs
                encrypted_update_assignments := encrypted_update_assignments || format(
                    '%I = pgp_sym_encrypt(NEW.%I::text, %L::text), ',
                    col.column_name, col.column_name, encryption_key
                );

                -- No primary key on this table: fall back to matching every
                -- column (decrypted) against OLD to identify the row for UPDATE/DELETE.
                IF pk_columns IS NULL THEN
                    update_pk_conditions := update_pk_conditions || format(
                        'convert_from(pgp_sym_decrypt(%I, ''%s'')::bytea, ''UTF-8'')::%s IS NOT DISTINCT FROM OLD.%I AND ',
                        col.column_name, encryption_key, structure_col_type, col.column_name
                    );
                    delete_pk_conditions := delete_pk_conditions || format(
                        'convert_from(pgp_sym_decrypt(%I, ''%s'')::bytea, ''UTF-8'')::%s IS NOT DISTINCT FROM OLD.%I AND ',
                        col.column_name, encryption_key, structure_col_type, col.column_name
                    );
                END IF;
            END IF;

        END LOOP;

        -- Table has no columns at all (e.g. sik.jns_perawatan_inap) — nothing
        -- to build a view/select-list/trigger-body from, so skip it.
        IF decrypt_select = '' THEN
            RAISE NOTICE 'Skipping %.%: table has no columns', tbl.table_schema, tbl.table_name;
            CONTINUE;
        END IF;

        decrypt_select := left(decrypt_select, length(decrypt_select) - 2);
        encrypted_insert_columns := left(encrypted_insert_columns, length(encrypted_insert_columns) - 2);
        encrypted_insert_values := left(encrypted_insert_values, length(encrypted_insert_values) - 2);
        encrypted_insert_columns_noid := left(encrypted_insert_columns_noid, length(encrypted_insert_columns_noid) - 2);
        encrypted_insert_values_noid := left(encrypted_insert_values_noid, length(encrypted_insert_values_noid) - 2);
        update_pk_conditions := left(update_pk_conditions, length(update_pk_conditions) - 5);
        delete_pk_conditions := left(delete_pk_conditions, length(delete_pk_conditions) - 5);
        encrypted_update_assignments := left(encrypted_update_assignments, length(encrypted_update_assignments) - 2);

        -- INSERT branch of the trigger: a plain static INSERT can't handle an
        -- identity PK, because the app normally omits it expecting Postgres
        -- to generate the value, but this function always names every
        -- column explicitly (including the PK) with a value from NEW — and
        -- an explicit value (even NULL) on a GENERATED identity column is
        -- rejected unless overridden. So when NEW.<pk> is NULL, insert
        -- without that column and let identity generate it; otherwise
        -- (e.g. bulk-copying pre-existing rows) insert it explicitly with
        -- OVERRIDING SYSTEM VALUE.
        IF identity_pk_col IS NOT NULL THEN
            values_clause_noid := CASE
                WHEN encrypted_insert_columns_noid = '' THEN 'DEFAULT VALUES'
                ELSE format('(%s) VALUES (%s)', encrypted_insert_columns_noid, encrypted_insert_values_noid)
            END;
            insert_clause := format(
                'IF NEW.%I IS NULL THEN
                    INSERT INTO %I.%I %s RETURNING %I INTO NEW.%I;
                ELSE
                    INSERT INTO %I.%I (%s) OVERRIDING SYSTEM VALUE VALUES (%s);
                END IF;',
                identity_pk_col,
                tbl.table_schema, tbl.table_name, values_clause_noid, identity_pk_col, identity_pk_col,
                tbl.table_schema, tbl.table_name, encrypted_insert_columns, encrypted_insert_values
            );
        ELSE
            insert_clause := format(
                'INSERT INTO %I.%I (%s) VALUES (%s);',
                tbl.table_schema, tbl.table_name, encrypted_insert_columns, encrypted_insert_values
            );
        END IF;

        plain_name = REPLACE(tbl.table_name, '_encrypted', '');
        -- Create view based on _encrypted tables
        EXECUTE format('
            CREATE OR REPLACE VIEW %I.%I AS SELECT %s FROM %I.%I;', 
            tbl.table_schema, plain_name, decrypt_select, tbl.table_schema, tbl.table_name
        );

        -- Create function to handle INSERTs, UPDATEs, DELETEs
        EXECUTE format(
            $func$
            CREATE OR REPLACE FUNCTION %I.%I_view_function()
            RETURNS trigger AS
                $body$
                BEGIN
                    IF TG_OP = 'INSERT' THEN
                        %s
                        RETURN NEW;
                    ELSIF TG_OP = 'UPDATE' THEN
                        UPDATE %I.%I SET %s WHERE %s;
                        RETURN NEW;
                    ELSIF TG_OP = 'DELETE' THEN
                        DELETE FROM %I.%I WHERE %s;
                        RETURN OLD;
                    END IF;
                    RETURN NULL;
                END
                $body$ LANGUAGE plpgsql;
            $func$,
                tbl.table_schema, plain_name,
                insert_clause,
                tbl.table_schema, tbl.table_name, encrypted_update_assignments, update_pk_conditions,
                tbl.table_schema, tbl.table_name, delete_pk_conditions
        );

        -- Create or replace view trigger
        EXECUTE format('
            CREATE OR REPLACE TRIGGER %I_view_trigger
            INSTEAD OF INSERT OR UPDATE OR DELETE ON %I.%I
            FOR EACH ROW EXECUTE FUNCTION %I.%I_view_function();',
            plain_name, tbl.table_schema, plain_name, tbl.table_schema, plain_name
        );
        RAISE NOTICE 'Completed view %.%: view of %.%', tbl.table_schema, plain_name, tbl.table_schema, tbl.table_name;
    END LOOP;
END
$$;
