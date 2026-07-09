DO
$$
DECLARE
    tbl RECORD;
    col RECORD;
    decrypt_select TEXT;
    pk_columns TEXT[];
    encryption_key TEXT := 'BismillahSidangNilaiA';
    audit_view_name TEXT;
    audit_table_name TEXT;
    extra_audit_view_values TEXT;
BEGIN
    FOR tbl IN
        SELECT table_schema, table_name
        FROM information_schema.tables
        WHERE table_type = 'BASE TABLE' --only include tables
            AND table_schema NOT IN ('pg_catalog', 'information_schema') -- exclude system tables
            AND table_name LIKE '%_structure'-- include _structure only
    LOOP    
        RAISE NOTICE '%', tbl.table_name;
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

        FOR col IN
            SELECT column_name, data_type, udt_schema, udt_name
            FROM information_schema.columns
            WHERE table_schema = tbl.table_schema AND table_name = tbl.table_name
            ORDER BY ordinal_position
        LOOP
            IF pk_columns IS NOT NULL AND col.column_name = ANY(pk_columns) THEN
                -- Handle SELECTs
                decrypt_select := decrypt_select ||
                    format('%I, ', col.column_name
                );
            ELSE
                -- Handle SELECTs
                -- schema-qualified: app's DB connection resets search_path to "public"
                decrypt_select := decrypt_select || format('convert_from(pgp_sym_decrypt(%I, ''%s'')::bytea, ''UTF-8'')::%s AS %I, ',
                    col.column_name,
                    encryption_key,
                    CASE WHEN col.data_type = 'USER-DEFINED' THEN format('%I.%I', col.udt_schema, col.udt_name) ELSE col.data_type END,
                    col.column_name
                );
            END IF;
        END LOOP;

        RAISE NOTICE '%', decrypt_select;
        extra_audit_view_values := format(
            'convert_from(pgp_sym_decrypt(changed_by, ''%s'')::bytea, ''UTF-8'')::UUID AS changed_by,'
            'pgp_sym_decrypt(user_ip, ''%s'')::TEXT AS user_ip,'
            'pgp_sym_decrypt(user_mac, ''%s'')::TEXT AS user_mac,'
            'pgp_sym_decrypt(action, ''%s'')::VARCHAR(10) AS action,'
            'pgp_sym_decrypt(changed_at, ''%s'')::TIMESTAMPTZ AS changed_at', 
            encryption_key,
            encryption_key, 
            encryption_key, 
            encryption_key,
            encryption_key);
        
        decrypt_select := decrypt_select || extra_audit_view_values;
        audit_view_name  = REPLACE(tbl.table_name, '_structure', '_audit_view');
        audit_table_name = REPLACE(tbl.table_name, '_structure', '_audit');

        -- Create view based on _audit tables
        EXECUTE format('
            CREATE OR REPLACE VIEW %I.%I AS SELECT %s FROM %I.%I;', 
            tbl.table_schema, audit_view_name, decrypt_select, tbl.table_schema, audit_table_name
        );
    END LOOP;
END
$$;
