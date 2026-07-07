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
BEGIN
    FOR tbl IN
        SELECT table_schema, table_name
        FROM information_schema.tables
        WHERE table_type = 'BASE TABLE' --only include tables
            AND table_schema NOT IN ('pg_catalog', 'information_schema', 'ref', 'sik') -- exclude system tables
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

        decrypt_select := '';
        encrypted_insert_columns := '';
        encrypted_insert_values := '';
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
                -- Handle UPDATEs
                update_pk_conditions := update_pk_conditions || 
                    format('%I = NEW.%I AND ', col.column_name, col.column_name
                );
                encrypted_update_assignments := encrypted_update_assignments || 
                    format('%I = NEW.%I, ', col.column_name, col.column_name
                );
                -- Handle DELETEs
                delete_pk_conditions := delete_pk_conditions || 
                    format('%I = OLD.%I AND ', col.column_name, col.column_name
                );
            ELSE
                -- Find the matching type from _structure
                SELECT 
                    CASE 
                        WHEN data_type = 'USER-DEFINED' THEN udt_name 
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
                    'pgp_sym_encrypt(NEW.%I::text, ''%s''), ', col.column_name, encryption_key
                );

                -- Handle UPDATEs
                encrypted_update_assignments := encrypted_update_assignments || format(
                    '%I = pgp_sym_encrypt(NEW.%I::text, ''%s''), ', 
                    col.column_name, col.column_name, encryption_key
                );
            END IF;

        END LOOP;

        decrypt_select := left(decrypt_select, length(decrypt_select) - 2);
        encrypted_insert_columns := left(encrypted_insert_columns, length(encrypted_insert_columns) - 2);
        encrypted_insert_values := left(encrypted_insert_values, length(encrypted_insert_values) - 2);
        update_pk_conditions := left(update_pk_conditions, length(update_pk_conditions) - 5);
        delete_pk_conditions := left(delete_pk_conditions, length(delete_pk_conditions) - 5);
        encrypted_update_assignments := left(encrypted_update_assignments, length(encrypted_update_assignments) - 2);

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
                        INSERT INTO %I.%I (%s) VALUES (%s);
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
                tbl.table_schema, tbl.table_name, encrypted_insert_columns, encrypted_insert_values,
                tbl.table_schema, tbl.table_name, encrypted_update_assignments, update_pk_conditions,
                tbl.table_schema, tbl.table_name, delete_pk_conditions
        );

        -- Create or replace view trigger
        EXECUTE format('
            CREATE TRIGGER %I_view_trigger 
            INSTEAD OF INSERT OR UPDATE OR DELETE ON %I.%I 
            FOR EACH ROW EXECUTE FUNCTION %I.%I_view_function();',
            plain_name, tbl.table_schema, plain_name, tbl.table_schema, plain_name
        );
        RAISE NOTICE 'Completed view %.%: view of %.%', tbl.table_schema, plain_name, tbl.table_schema, tbl.table_name;
    END LOOP;
END
$$;
