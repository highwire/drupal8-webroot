CREATE KEYSPACE IF NOT EXISTS atomlite
WITH durable_writes = true
AND replication = {
  'class' : 'SimpleStrategy',
  'replication_factor' : 1
};

CREATE TABLE IF NOT EXISTS atomlite.atom_lite (
  mime_type text,
  corpus text,
  uri text,
  app_edited_date timestamp,
  extraction_date timestamp,
  payload text,
  payload_signature text,
  policy_date timestamp,
  policy_id timeuuid,
  update_date timestamp,
  PRIMARY KEY (( mime_type, corpus ), uri)
) WITH CLUSTERING ORDER BY ( uri ASC )
AND bloom_filter_fp_chance = 0.01
AND comment = ''
AND dclocal_read_repair_chance = 0.1
AND default_time_to_live = 0
AND gc_grace_seconds = 864000
AND max_index_interval = 2048
AND memtable_flush_period_in_ms = 0
AND min_index_interval = 128
AND read_repair_chance = 0.0
AND speculative_retry = '99.0PERCENTILE'
AND caching = {
  'keys' : 'ALL',
  'rows_per_partition' : 'NONE'
}
AND compression = {
  'sstable_compression' : 'LZ4Compressor'
}
AND compaction = {
  'class' : 'SizeTieredCompactionStrategy'
};

CREATE TABLE IF NOT EXISTS atomlite.policy (
  id timeuuid,
  format text,
  mime_type LIST<text>,
  name text,
  policy_id text,
  signature text,
  source text,
  updated timestamp,
  url text,
  PRIMARY KEY (id)
) WITH bloom_filter_fp_chance = 0.01
AND comment = ''
AND dclocal_read_repair_chance = 0.1
AND default_time_to_live = 0
AND gc_grace_seconds = 864000
AND max_index_interval = 2048
AND memtable_flush_period_in_ms = 0
AND min_index_interval = 128
AND read_repair_chance = 0.0
AND speculative_retry = '99.0PERCENTILE'
AND caching = {
  'keys' : 'ALL',
  'rows_per_partition' : 'NONE'
}
AND compression = {
  'sstable_compression' : 'LZ4Compressor'
}
AND compaction = {
  'class' : 'SizeTieredCompactionStrategy'
};

CREATE INDEX IF NOT EXISTS atomlite_policy_policy_id_idx ON atomlite.policy (policy_id);

CREATE INDEX IF NOT EXISTS atomlite_policy_updated_idx ON atomlite.policy (updated);