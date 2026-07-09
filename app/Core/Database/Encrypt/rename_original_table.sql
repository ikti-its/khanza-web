DO $$
DECLARE
    tbl RECORD;
BEGIN
    IF current_database() <> 'khanza_db' THEN
        RAISE NOTICE 'Skipping: not in khanza_db (current: %)', current_database();
        RETURN;
    END IF;

    FOR tbl IN
        SELECT table_schema, table_name
        FROM information_schema.tables
        WHERE table_type = 'BASE TABLE'
          AND table_schema NOT IN ('pg_catalog', 'information_schema')  -- skip system schemas
          AND table_name NOT LIKE '%\_structure' ESCAPE '\'  -- already renamed
          AND table_name NOT LIKE '%\_encrypted' ESCAPE '\'  -- encrypt output, not source data
          AND table_name NOT LIKE '%\_audit' ESCAPE '\'      -- audit trail, not source data
    LOOP
        EXECUTE format(
            'ALTER TABLE %I.%I RENAME TO %I;',
            tbl.table_schema,
            tbl.table_name,
            tbl.table_name || '_structure'
        );
        RAISE NOTICE 'Renamed %.% to %.%', 
            tbl.table_schema, tbl.table_name, 
            tbl.table_schema, tbl.table_name || '_structure';
    END LOOP;
END $$;
