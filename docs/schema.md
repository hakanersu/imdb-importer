# Final database schema

This is the final database schema.

## Titles

```sql
BEGIN;

-- CREATE TABLE "titles" ---------------------------------------
CREATE TABLE "public"."titles" ( 
	"id" BigInt DEFAULT nextval('titles_id_seq'::regclass) NOT NULL,
	"tconst" Character Varying( 16 ) NOT NULL,
	"title_type" Character Varying( 16 ),
	"primary_title" Character Varying( 255 ),
	"original_title" Character Varying( 255 ),
	"is_adult" Character Varying( 16 ),
	"start_year" Integer,
	"end_year" Integer,
	"runtime_minutes" Integer,
	"genres" Character Varying( 64 ),
	"weight" Double Precision DEFAULT '0'::double precision NOT NULL,
	"tsv_title_text" TSVector,
	PRIMARY KEY ( "id" ) );
 ;
-- -------------------------------------------------------------

-- CREATE INDEX "titles_tconst_index" --------------------------
CREATE INDEX "titles_tconst_index" ON "public"."titles" USING btree( "tconst" Asc NULLS Last );
-- -------------------------------------------------------------

-- CREATE INDEX "titles_weight_index" --------------------------
CREATE INDEX "titles_weight_index" ON "public"."titles" USING btree( "weight" Asc NULLS Last );
-- -------------------------------------------------------------

-- CREATE INDEX "titles_weight_idx" ----------------------------
CREATE INDEX "titles_weight_idx" ON "public"."titles" USING btree( "weight" Asc NULLS Last );
-- -------------------------------------------------------------

-- CREATE INDEX "tsv_title_text_idx" ---------------------------
CREATE INDEX "tsv_title_text_idx" ON "public"."titles" USING gin( "tsv_title_text" );
-- -------------------------------------------------------------

COMMIT;
```

## Episodes

```sql
BEGIN;

-- CREATE TABLE "episodes" -------------------------------------
CREATE TABLE "public"."episodes" ( 
	"id" BigInt DEFAULT nextval('episodes_id_seq'::regclass) NOT NULL,
	"tconst" Character Varying( 16 ) NOT NULL,
	"parent_tconst" Character Varying( 16 ),
	"season_number" Character Varying( 16 ),
	"episode_number" Character Varying( 16 ),
	PRIMARY KEY ( "id" ) );
 ;
-- -------------------------------------------------------------

-- CREATE INDEX "episodes_tconst_index" ------------------------
CREATE INDEX "episodes_tconst_index" ON "public"."episodes" USING btree( "tconst" Asc NULLS Last );
-- -------------------------------------------------------------

-- CREATE INDEX "episodes_tconst_idx" --------------------------
CREATE INDEX "episodes_tconst_idx" ON "public"."episodes" USING btree( "tconst" Asc NULLS Last );
-- -------------------------------------------------------------

-- CREATE INDEX "episodes_parent_tconst_idx" -------------------
CREATE INDEX "episodes_parent_tconst_idx" ON "public"."episodes" USING btree( "parent_tconst" Asc NULLS Last );
-- -------------------------------------------------------------

COMMIT;

```

## Ratings

```sql
BEGIN;

-- CREATE TABLE "ratings" --------------------------------------
CREATE TABLE "public"."ratings" ( 
	"id" BigInt DEFAULT nextval('ratings_id_seq'::regclass) NOT NULL,
	"tconst" Character Varying( 16 ),
	"average_rating" Numeric( 3, 1 ),
	"num_votes" Integer,
	PRIMARY KEY ( "id" ) );
 ;
-- -------------------------------------------------------------

-- CREATE INDEX "ratings_tconst_idx" ---------------------------
CREATE INDEX "ratings_tconst_idx" ON "public"."ratings" USING btree( "tconst" Asc NULLS Last );
-- -------------------------------------------------------------

COMMIT;

```


## Akas

```sql
BEGIN;

-- CREATE TABLE "akas" -----------------------------------------
CREATE TABLE "public"."akas" ( 
	"id" BigInt DEFAULT nextval('akas_id_seq'::regclass) NOT NULL,
	"title_id" Character Varying( 16 ) NOT NULL,
	"ordering" Character Varying( 16 ),
	"title" Text,
	"region" Character Varying( 16 ),
	"language" Character Varying( 16 ),
	"types" Character Varying( 32 ),
	"attributes" Character Varying( 255 ),
	"is_original_title" Character Varying( 16 ),
	PRIMARY KEY ( "id" ) );
 ;
-- -------------------------------------------------------------

-- CREATE INDEX "akas_title_id_index" --------------------------
CREATE INDEX "akas_title_id_index" ON "public"."akas" USING btree( "title_id" Asc NULLS Last );
-- -------------------------------------------------------------

COMMIT;
```

## Crews

```sql
BEGIN;

-- CREATE TABLE "crews" ----------------------------------------
CREATE TABLE "public"."crews" ( 
	"id" BigInt DEFAULT nextval('crews_id_seq'::regclass) NOT NULL,
	"tconst" Character Varying( 16 ),
	"directors" Text,
	"writers" Text,
	PRIMARY KEY ( "id" ) );
 ;
-- -------------------------------------------------------------

-- CREATE INDEX "crews_tconst_idx" -----------------------------
CREATE INDEX "crews_tconst_idx" ON "public"."crews" USING btree( "tconst" Asc NULLS Last );
-- -------------------------------------------------------------

COMMIT;
```

## Names

```sql
BEGIN;

-- CREATE TABLE "names" ----------------------------------------
CREATE TABLE "public"."names" ( 
	"id" BigInt DEFAULT nextval('names_id_seq'::regclass) NOT NULL,
	"nconst" Character Varying( 12 ) NOT NULL,
	"primary_name" Character Varying( 255 ),
	"birth_year" Character Varying( 16 ),
	"death_year" Character Varying( 16 ),
	"primary_profession" Character Varying( 255 ),
	"known_for_titles" Character Varying( 255 ),
	PRIMARY KEY ( "id" ) );
 ;
-- -------------------------------------------------------------

-- CREATE INDEX "names_nconst_index" ---------------------------
CREATE INDEX "names_nconst_index" ON "public"."names" USING btree( "nconst" Asc NULLS Last );
-- -------------------------------------------------------------

COMMIT;
```

## Principals

```sql
BEGIN;

-- CREATE TABLE "principals" -----------------------------------
CREATE TABLE "public"."principals" ( 
	"id" BigInt DEFAULT nextval('principals_id_seq'::regclass) NOT NULL,
	"tconst" Character Varying( 16 ) NOT NULL,
	"nconst" Text,
	"category" Text,
	"job" Text,
	"characters" Text,
	"ordering" Text,
	PRIMARY KEY ( "id" ) );
 ;
-- -------------------------------------------------------------

-- CREATE INDEX "principals_tconst_index" ----------------------
CREATE INDEX "principals_tconst_index" ON "public"."principals" USING btree( "tconst" Asc NULLS Last );
-- -------------------------------------------------------------

COMMIT;

```
