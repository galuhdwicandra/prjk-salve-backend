--
-- PostgreSQL database dump
--

\restrict vp14ClUYuwHtyC5n5DV8Dq1TIOZdbJXJNouRcAMk07x90yxJ4liOTWe5yTCa0mV

-- Dumped from database version 18.1 (Ubuntu 18.1-1.pgdg24.04+2)
-- Dumped by pg_dump version 18.1 (Ubuntu 18.1-1.pgdg24.04+2)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: SCHEMA "public"; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON SCHEMA "public" IS 'standard public schema';


--
-- Name: pgcrypto; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS "pgcrypto" WITH SCHEMA "public";


--
-- Name: EXTENSION "pgcrypto"; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON EXTENSION "pgcrypto" IS 'cryptographic functions';


SET default_tablespace = '';

SET default_table_access_method = "heap";

--
-- Name: branches; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "public"."branches" (
    "id" "uuid" NOT NULL,
    "code" character varying(32) NOT NULL,
    "name" character varying(150) NOT NULL,
    "address" character varying(255),
    "invoice_prefix" character varying(8) DEFAULT 'SLV'::character varying NOT NULL,
    "reset_policy" character varying(255) DEFAULT 'monthly'::character varying NOT NULL,
    "created_at" timestamp(0) without time zone,
    "updated_at" timestamp(0) without time zone,
    CONSTRAINT "branches_reset_policy_check" CHECK ((("reset_policy")::"text" = ANY ((ARRAY['monthly'::character varying, 'never'::character varying])::"text"[])))
);


--
-- Name: cache; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "public"."cache" (
    "key" character varying(255) NOT NULL,
    "value" "text" NOT NULL,
    "expiration" integer NOT NULL
);


--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "public"."cache_locks" (
    "key" character varying(255) NOT NULL,
    "owner" character varying(255) NOT NULL,
    "expiration" integer NOT NULL
);


--
-- Name: customers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "public"."customers" (
    "id" "uuid" DEFAULT "gen_random_uuid"() NOT NULL,
    "branch_id" "uuid" NOT NULL,
    "name" character varying(150) NOT NULL,
    "whatsapp" character varying(32) NOT NULL,
    "address" character varying(255),
    "notes" "text",
    "created_at" timestamp(0) with time zone,
    "updated_at" timestamp(0) with time zone
);


--
-- Name: deliveries; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "public"."deliveries" (
    "id" "uuid" NOT NULL,
    "order_id" "uuid" NOT NULL,
    "type" character varying(20) NOT NULL,
    "zone_id" "uuid",
    "fee" numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    "assigned_to" bigint,
    "auto_assigned" boolean DEFAULT false NOT NULL,
    "status" character varying(32) DEFAULT 'CREATED'::character varying NOT NULL,
    "handover_photo" character varying(255),
    "created_at" timestamp(0) without time zone,
    "updated_at" timestamp(0) without time zone
);


--
-- Name: delivery_events; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "public"."delivery_events" (
    "id" "uuid" NOT NULL,
    "delivery_id" "uuid" NOT NULL,
    "status" character varying(32) NOT NULL,
    "note" character varying(200),
    "created_at" timestamp(0) without time zone,
    "updated_at" timestamp(0) without time zone
);


--
-- Name: expenses; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "public"."expenses" (
    "id" "uuid" NOT NULL,
    "branch_id" "uuid" NOT NULL,
    "category" character varying(100) NOT NULL,
    "amount" numeric(12,2) NOT NULL,
    "note" "text",
    "proof_path" character varying(255),
    "created_at" timestamp(0) without time zone,
    "updated_at" timestamp(0) without time zone
);


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "public"."failed_jobs" (
    "id" bigint NOT NULL,
    "uuid" character varying(255) NOT NULL,
    "connection" "text" NOT NULL,
    "queue" "text" NOT NULL,
    "payload" "text" NOT NULL,
    "exception" "text" NOT NULL,
    "failed_at" timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE "public"."failed_jobs_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE "public"."failed_jobs_id_seq" OWNED BY "public"."failed_jobs"."id";


--
-- Name: invoice_counters; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "public"."invoice_counters" (
    "id" "uuid" NOT NULL,
    "branch_id" "uuid" NOT NULL,
    "prefix" character varying(8) DEFAULT 'SLV'::character varying NOT NULL,
    "seq" integer DEFAULT 0 NOT NULL,
    "reset_policy" character varying(255) DEFAULT 'monthly'::character varying NOT NULL,
    "last_reset_month" character(6),
    "created_at" timestamp(0) without time zone,
    "updated_at" timestamp(0) without time zone,
    CONSTRAINT "invoice_counters_reset_policy_check" CHECK ((("reset_policy")::"text" = ANY ((ARRAY['monthly'::character varying, 'never'::character varying])::"text"[])))
);


--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "public"."job_batches" (
    "id" character varying(255) NOT NULL,
    "name" character varying(255) NOT NULL,
    "total_jobs" integer NOT NULL,
    "pending_jobs" integer NOT NULL,
    "failed_jobs" integer NOT NULL,
    "failed_job_ids" "text" NOT NULL,
    "options" "text",
    "cancelled_at" integer,
    "created_at" integer NOT NULL,
    "finished_at" integer
);


--
-- Name: jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "public"."jobs" (
    "id" bigint NOT NULL,
    "queue" character varying(255) NOT NULL,
    "payload" "text" NOT NULL,
    "attempts" smallint NOT NULL,
    "reserved_at" integer,
    "available_at" integer NOT NULL,
    "created_at" integer NOT NULL
);


--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE "public"."jobs_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE "public"."jobs_id_seq" OWNED BY "public"."jobs"."id";


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "public"."migrations" (
    "id" integer NOT NULL,
    "migration" character varying(255) NOT NULL,
    "batch" integer NOT NULL
);


--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE "public"."migrations_id_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE "public"."migrations_id_seq" OWNED BY "public"."migrations"."id";


--
-- Name: model_has_permissions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "public"."model_has_permissions" (
    "permission_id" bigint NOT NULL,
    "model_type" character varying(255) NOT NULL,
    "model_id" bigint NOT NULL
);


--
-- Name: model_has_roles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "public"."model_has_roles" (
    "role_id" bigint NOT NULL,
    "model_type" character varying(255) NOT NULL,
    "model_id" bigint NOT NULL
);


--
-- Name: order_items; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "public"."order_items" (
    "id" "uuid" NOT NULL,
    "order_id" "uuid" NOT NULL,
    "service_id" "uuid" NOT NULL,
    "qty" numeric(10,2) NOT NULL,
    "price" numeric(12,2) NOT NULL,
    "total" numeric(12,2) NOT NULL,
    "note" character varying(200),
    "created_at" timestamp(0) without time zone,
    "updated_at" timestamp(0) without time zone
);


--
-- Name: order_photos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "public"."order_photos" (
    "id" "uuid" NOT NULL,
    "order_id" "uuid" NOT NULL,
    "kind" character varying(255) NOT NULL,
    "path" character varying(255) NOT NULL,
    "created_at" timestamp(0) without time zone,
    "updated_at" timestamp(0) without time zone,
    CONSTRAINT "order_photos_kind_check" CHECK ((("kind")::"text" = ANY ((ARRAY['before'::character varying, 'after'::character varying])::"text"[])))
);


--
-- Name: order_vouchers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "public"."order_vouchers" (
    "id" "uuid" DEFAULT "gen_random_uuid"() NOT NULL,
    "order_id" "uuid" NOT NULL,
    "voucher_id" "uuid" NOT NULL,
    "applied_amount" numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    "applied_by" bigint,
    "applied_at" timestamp(0) with time zone,
    "created_at" timestamp(0) with time zone,
    "updated_at" timestamp(0) with time zone
);


--
-- Name: orders; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "public"."orders" (
    "id" "uuid" NOT NULL,
    "branch_id" "uuid" NOT NULL,
    "customer_id" "uuid",
    "number" character varying(40) NOT NULL,
    "status" character varying(20) NOT NULL,
    "subtotal" numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    "discount" numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    "grand_total" numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    "paid_amount" numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    "due_amount" numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    "notes" "text",
    "created_at" timestamp(0) without time zone,
    "updated_at" timestamp(0) without time zone,
    "payment_status" character varying(20) DEFAULT 'PENDING'::character varying NOT NULL,
    "dp_amount" numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    "paid_at" timestamp(0) without time zone,
    "invoice_no" character varying(40),
    "created_by" bigint
);


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "public"."password_reset_tokens" (
    "email" character varying(255) NOT NULL,
    "token" character varying(255) NOT NULL,
    "created_at" timestamp(0) without time zone
);


--
-- Name: payments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "public"."payments" (
    "id" "uuid" DEFAULT "gen_random_uuid"() NOT NULL,
    "order_id" "uuid" NOT NULL,
    "method" character varying(255) DEFAULT 'PENDING'::character varying NOT NULL,
    "amount" numeric(12,2) NOT NULL,
    "paid_at" timestamp(0) with time zone,
    "note" character varying(200),
    "created_at" timestamp(0) with time zone,
    "updated_at" timestamp(0) with time zone,
    CONSTRAINT "payments_method_check" CHECK ((("method")::"text" = ANY ((ARRAY['PENDING'::character varying, 'DP'::character varying, 'CASH'::character varying, 'QRIS'::character varying, 'TRANSFER'::character varying])::"text"[])))
);


--
-- Name: permissions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "public"."permissions" (
    "id" bigint NOT NULL,
    "name" character varying(255) NOT NULL,
    "guard_name" character varying(255) NOT NULL,
    "created_at" timestamp(0) without time zone,
    "updated_at" timestamp(0) without time zone
);


--
-- Name: permissions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE "public"."permissions_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: permissions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE "public"."permissions_id_seq" OWNED BY "public"."permissions"."id";


--
-- Name: personal_access_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "public"."personal_access_tokens" (
    "id" bigint NOT NULL,
    "tokenable_type" character varying(255) NOT NULL,
    "tokenable_id" bigint NOT NULL,
    "name" "text" NOT NULL,
    "token" character varying(64) NOT NULL,
    "abilities" "text",
    "last_used_at" timestamp(0) without time zone,
    "expires_at" timestamp(0) without time zone,
    "created_at" timestamp(0) without time zone,
    "updated_at" timestamp(0) without time zone
);


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE "public"."personal_access_tokens_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE "public"."personal_access_tokens_id_seq" OWNED BY "public"."personal_access_tokens"."id";


--
-- Name: receivables; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "public"."receivables" (
    "id" "uuid" DEFAULT "gen_random_uuid"() NOT NULL,
    "order_id" "uuid" NOT NULL,
    "remaining_amount" numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    "status" character varying(255) DEFAULT 'OPEN'::character varying NOT NULL,
    "due_date" "date",
    "created_at" timestamp(0) with time zone,
    "updated_at" timestamp(0) with time zone,
    CONSTRAINT "receivables_status_check" CHECK ((("status")::"text" = ANY ((ARRAY['OPEN'::character varying, 'PARTIAL'::character varying, 'SETTLED'::character varying])::"text"[])))
);


--
-- Name: role_has_permissions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "public"."role_has_permissions" (
    "permission_id" bigint NOT NULL,
    "role_id" bigint NOT NULL
);


--
-- Name: roles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "public"."roles" (
    "id" bigint NOT NULL,
    "name" character varying(255) NOT NULL,
    "guard_name" character varying(255) NOT NULL,
    "created_at" timestamp(0) without time zone,
    "updated_at" timestamp(0) without time zone
);


--
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE "public"."roles_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE "public"."roles_id_seq" OWNED BY "public"."roles"."id";


--
-- Name: service_categories; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "public"."service_categories" (
    "id" "uuid" NOT NULL,
    "name" character varying(120) NOT NULL,
    "is_active" boolean DEFAULT true NOT NULL,
    "created_at" timestamp(0) without time zone,
    "updated_at" timestamp(0) without time zone
);


--
-- Name: service_prices; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "public"."service_prices" (
    "id" "uuid" NOT NULL,
    "service_id" "uuid" NOT NULL,
    "branch_id" "uuid" NOT NULL,
    "price" numeric(14,2) NOT NULL,
    "created_at" timestamp(0) without time zone,
    "updated_at" timestamp(0) without time zone
);


--
-- Name: services; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "public"."services" (
    "id" "uuid" NOT NULL,
    "category_id" "uuid" NOT NULL,
    "name" character varying(150) NOT NULL,
    "unit" character varying(32) NOT NULL,
    "price_default" numeric(14,2) DEFAULT '0'::numeric NOT NULL,
    "is_active" boolean DEFAULT true NOT NULL,
    "created_at" timestamp(0) without time zone,
    "updated_at" timestamp(0) without time zone
);


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "public"."sessions" (
    "id" character varying(255) NOT NULL,
    "user_id" bigint,
    "ip_address" character varying(45),
    "user_agent" "text",
    "payload" "text" NOT NULL,
    "last_activity" integer NOT NULL
);


--
-- Name: users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "public"."users" (
    "id" bigint NOT NULL,
    "name" character varying(255) NOT NULL,
    "email" character varying(255) NOT NULL,
    "email_verified_at" timestamp(0) without time zone,
    "password" character varying(255) NOT NULL,
    "remember_token" character varying(100),
    "created_at" timestamp(0) without time zone,
    "updated_at" timestamp(0) without time zone,
    "is_active" boolean DEFAULT true NOT NULL,
    "branch_id" "uuid"
);


--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE "public"."users_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE "public"."users_id_seq" OWNED BY "public"."users"."id";


--
-- Name: vouchers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "public"."vouchers" (
    "id" "uuid" DEFAULT "gen_random_uuid"() NOT NULL,
    "branch_id" "uuid",
    "code" character varying(40) NOT NULL,
    "type" character varying(255) NOT NULL,
    "value" numeric(12,2) NOT NULL,
    "start_at" timestamp(0) with time zone,
    "end_at" timestamp(0) with time zone,
    "min_total" numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    "usage_limit" integer,
    "active" boolean DEFAULT true NOT NULL,
    "created_at" timestamp(0) with time zone,
    "updated_at" timestamp(0) with time zone,
    CONSTRAINT "vouchers_type_check" CHECK ((("type")::"text" = ANY ((ARRAY['PERCENT'::character varying, 'NOMINAL'::character varying])::"text"[])))
);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."failed_jobs" ALTER COLUMN "id" SET DEFAULT "nextval"('"public"."failed_jobs_id_seq"'::"regclass");


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."jobs" ALTER COLUMN "id" SET DEFAULT "nextval"('"public"."jobs_id_seq"'::"regclass");


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."migrations" ALTER COLUMN "id" SET DEFAULT "nextval"('"public"."migrations_id_seq"'::"regclass");


--
-- Name: permissions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."permissions" ALTER COLUMN "id" SET DEFAULT "nextval"('"public"."permissions_id_seq"'::"regclass");


--
-- Name: personal_access_tokens id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."personal_access_tokens" ALTER COLUMN "id" SET DEFAULT "nextval"('"public"."personal_access_tokens_id_seq"'::"regclass");


--
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."roles" ALTER COLUMN "id" SET DEFAULT "nextval"('"public"."roles_id_seq"'::"regclass");


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."users" ALTER COLUMN "id" SET DEFAULT "nextval"('"public"."users_id_seq"'::"regclass");


--
-- Data for Name: branches; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."branches" ("id", "code", "name", "address", "invoice_prefix", "reset_policy", "created_at", "updated_at") FROM stdin;
71adee1b-91d7-43cc-a712-9eaac873c6a5	CBG-001	Cabang Utama	Alamat Cabang Utama	SLV	monthly	2025-11-25 14:29:33	2025-11-25 14:29:33
\.


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."cache" ("key", "value", "expiration") FROM stdin;
salve-cache-spatie.permission.cache	a:3:{s:5:"alias";a:0:{}s:11:"permissions";a:0:{}s:5:"roles";a:0:{}}	1764405869
\.


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."cache_locks" ("key", "owner", "expiration") FROM stdin;
\.


--
-- Data for Name: customers; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."customers" ("id", "branch_id", "name", "whatsapp", "address", "notes", "created_at", "updated_at") FROM stdin;
d699897e-14c0-417c-b745-5904d8e554ec	71adee1b-91d7-43cc-a712-9eaac873c6a5	Customer 1	081214695222	Permata Biru Block Ar.06	\N	2025-11-25 14:34:55+07	2025-11-25 14:34:55+07
1ec7273c-d85e-4d92-a10b-545820df93b8	71adee1b-91d7-43cc-a712-9eaac873c6a5	galuh	085865809424	Permata	\N	2025-11-25 14:36:35+07	2025-11-25 14:36:35+07
\.


--
-- Data for Name: deliveries; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."deliveries" ("id", "order_id", "type", "zone_id", "fee", "assigned_to", "auto_assigned", "status", "handover_photo", "created_at", "updated_at") FROM stdin;
019aba00-13be-72ac-9670-48efb8385682	019ab9ff-23cc-7064-978b-e932acbffe33	delivery	\N	0.00	5	t	ASSIGNED	\N	2025-11-25 14:52:38	2025-11-25 14:52:38
019aba2d-b26d-705c-ae5b-62dc30e0ebde	019aba2d-6a28-72ef-b18c-8c7a1f7d318e	delivery	\N	0.00	5	t	ASSIGNED	\N	2025-11-25 15:42:28	2025-11-25 15:42:28
\.


--
-- Data for Name: delivery_events; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."delivery_events" ("id", "delivery_id", "status", "note", "created_at", "updated_at") FROM stdin;
019aba00-13c1-7164-a863-8a362a0ca23c	019aba00-13be-72ac-9670-48efb8385682	CREATED	Delivery created	2025-11-25 14:52:38	2025-11-25 14:52:38
019aba00-13da-7273-a32c-66697e5beaf8	019aba00-13be-72ac-9670-48efb8385682	ASSIGNED	Auto-assigned courier #5	2025-11-25 14:52:38	2025-11-25 14:52:38
019aba2d-b272-7083-9cdd-5abfd5f3b2ff	019aba2d-b26d-705c-ae5b-62dc30e0ebde	CREATED	Delivery created	2025-11-25 15:42:28	2025-11-25 15:42:28
019aba2d-b282-7311-8289-85e728c2f934	019aba2d-b26d-705c-ae5b-62dc30e0ebde	ASSIGNED	Auto-assigned courier #5	2025-11-25 15:42:28	2025-11-25 15:42:28
\.


--
-- Data for Name: expenses; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."expenses" ("id", "branch_id", "category", "amount", "note", "proof_path", "created_at", "updated_at") FROM stdin;
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."failed_jobs" ("id", "uuid", "connection", "queue", "payload", "exception", "failed_at") FROM stdin;
\.


--
-- Data for Name: invoice_counters; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."invoice_counters" ("id", "branch_id", "prefix", "seq", "reset_policy", "last_reset_month", "created_at", "updated_at") FROM stdin;
019ab9f5-1df2-719c-ab37-1f6b4ef59514	71adee1b-91d7-43cc-a712-9eaac873c6a5	SLV	7503	never	\N	2025-11-25 14:40:40	2025-11-25 15:42:09
\.


--
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."job_batches" ("id", "name", "total_jobs", "pending_jobs", "failed_jobs", "failed_job_ids", "options", "cancelled_at", "created_at", "finished_at") FROM stdin;
\.


--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."jobs" ("id", "queue", "payload", "attempts", "reserved_at", "available_at", "created_at") FROM stdin;
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."migrations" ("id", "migration", "batch") FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2025_10_21_115731_create_personal_access_tokens_table	1
5	2025_10_21_115843_create_permission_tables	1
6	2025_10_21_210811_add_branch_id_and_is_active_to_users_table	1
7	2025_10_22_174101_create_branches_table	1
8	2025_10_22_174120_alter_users_branch_to_uuid	1
9	2025_10_22_174133_create_invoice_counters_table	1
10	2025_10_22_191246_create_service_categories_table	1
11	2025_10_22_191311_create_services_table	1
12	2025_10_22_191325_create_service_prices_table	1
13	2025_10_23_001451_create_customers_table	1
14	2025_10_30_001505_create_orders_table	1
15	2025_10_30_001918_create_order_items_table	1
16	2025_10_30_003346_create_order_photos_table	1
17	2025_10_30_171044_fix_orders_unique_number_per_branch	1
18	2025_10_30_172859_alter_orders_add_payment_columns	1
19	2025_10_30_172919_create_payments_table	1
20	2025_10_30_172932_create_receivables_table	1
21	2025_11_05_184536_create_deliveries_table	1
22	2025_11_05_184557_create_delivery_events_table	1
23	2025_11_08_220927_create_vouchers_table	1
24	2025_11_08_223242_create_order_vouchers_table	1
25	2025_11_09_162010_create_receivables_table	1
26	2025_11_14_201006_create_expenses_table	1
27	2025_11_17_013822_add_dashboard_helper_indexes	1
28	2025_11_21_174811_make_invoice_no_unique_on_orders	1
29	2025_11_25_145010_fix_orders_created_by_to_bigint	2
\.


--
-- Data for Name: model_has_permissions; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."model_has_permissions" ("permission_id", "model_type", "model_id") FROM stdin;
\.


--
-- Data for Name: model_has_roles; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."model_has_roles" ("role_id", "model_type", "model_id") FROM stdin;
1	App\\Models\\User	1
2	App\\Models\\User	2
4	App\\Models\\User	4
5	App\\Models\\User	5
3	App\\Models\\User	3
\.


--
-- Data for Name: order_items; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."order_items" ("id", "order_id", "service_id", "qty", "price", "total", "note", "created_at", "updated_at") FROM stdin;
019ab9ff-23d5-7257-8d9d-458178fc644d	019ab9ff-23cc-7064-978b-e932acbffe33	2f310fc1-a7ca-4b4e-88ff-391392b35215	1.00	125000.00	125000.00	\N	2025-11-25 14:51:37	2025-11-25 14:51:37
019aba2d-6a2d-7191-a102-2d1f28ca1257	019aba2d-6a28-72ef-b18c-8c7a1f7d318e	fde10d28-7dc9-4ffc-8630-b71e75b85345	1.00	50000.00	50000.00	\N	2025-11-25 15:42:09	2025-11-25 15:42:09
\.


--
-- Data for Name: order_photos; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."order_photos" ("id", "order_id", "kind", "path", "created_at", "updated_at") FROM stdin;
\.


--
-- Data for Name: order_vouchers; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."order_vouchers" ("id", "order_id", "voucher_id", "applied_amount", "applied_by", "applied_at", "created_at", "updated_at") FROM stdin;
\.


--
-- Data for Name: orders; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."orders" ("id", "branch_id", "customer_id", "number", "status", "subtotal", "discount", "grand_total", "paid_amount", "due_amount", "notes", "created_at", "updated_at", "payment_status", "dp_amount", "paid_at", "invoice_no", "created_by") FROM stdin;
019ab9ff-23cc-7064-978b-e932acbffe33	71adee1b-91d7-43cc-a712-9eaac873c6a5	1ec7273c-d85e-4d92-a10b-545820df93b8	SLV-202511-007502	DELIVERING	125000.00	0.00	125000.00	125000.00	0.00	\N	2025-11-25 14:51:37	2025-11-25 14:57:34	PAID	0.00	2025-11-25 07:57:00	INV-25-11-7502	3
019aba2d-6a28-72ef-b18c-8c7a1f7d318e	71adee1b-91d7-43cc-a712-9eaac873c6a5	d699897e-14c0-417c-b745-5904d8e554ec	SLV-202511-007503	DELIVERING	50000.00	0.00	50000.00	50000.00	0.00	\N	2025-11-25 15:42:09	2025-11-25 15:42:28	PAID	0.00	2025-11-25 08:42:12	INV-25-11-7503	3
\.


--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."password_reset_tokens" ("email", "token", "created_at") FROM stdin;
\.


--
-- Data for Name: payments; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."payments" ("id", "order_id", "method", "amount", "paid_at", "note", "created_at", "updated_at") FROM stdin;
640c923b-92c1-4577-8d96-57872cbf0f87	019ab9ff-23cc-7064-978b-e932acbffe33	CASH	125000.00	2025-11-25 07:57:00+07	\N	2025-11-25 14:57:34+07	2025-11-25 14:57:34+07
9ee7ffdb-3fdf-4b1c-9c2f-c0f798e066d2	019aba2d-6a28-72ef-b18c-8c7a1f7d318e	CASH	50000.00	2025-11-25 08:42:12+07	\N	2025-11-25 15:42:10+07	2025-11-25 15:42:10+07
\.


--
-- Data for Name: permissions; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."permissions" ("id", "name", "guard_name", "created_at", "updated_at") FROM stdin;
\.


--
-- Data for Name: personal_access_tokens; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."personal_access_tokens" ("id", "tokenable_type", "tokenable_id", "name", "token", "abilities", "last_used_at", "expires_at", "created_at", "updated_at") FROM stdin;
9	App\\Models\\User	2	auth-token	9d2ae5ae75772ae348f2e6d065accbdf5f7669da53a2a4be7e5943c29a2e4f19	["*"]	2025-11-28 15:44:40	\N	2025-11-28 15:44:27	2025-11-28 15:44:40
\.


--
-- Data for Name: receivables; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."receivables" ("id", "order_id", "remaining_amount", "status", "due_date", "created_at", "updated_at") FROM stdin;
740a42e2-dff4-46f2-a4e2-55b4d0117010	019ab9ff-23cc-7064-978b-e932acbffe33	0.00	SETTLED	\N	2025-11-25 14:51:37+07	2025-11-25 14:57:34+07
8c500778-e70d-4cf9-8223-d0933d066e20	019aba2d-6a28-72ef-b18c-8c7a1f7d318e	0.00	SETTLED	\N	2025-11-25 15:42:09+07	2025-11-25 15:42:10+07
\.


--
-- Data for Name: role_has_permissions; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."role_has_permissions" ("permission_id", "role_id") FROM stdin;
\.


--
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."roles" ("id", "name", "guard_name", "created_at", "updated_at") FROM stdin;
1	Superadmin	web	2025-11-25 14:29:32	2025-11-25 14:29:32
2	Admin Cabang	web	2025-11-25 14:29:32	2025-11-25 14:29:32
3	Kasir	web	2025-11-25 14:29:32	2025-11-25 14:29:32
4	Petugas Cuci	web	2025-11-25 14:29:32	2025-11-25 14:29:32
5	Kurir	web	2025-11-25 14:29:32	2025-11-25 14:29:32
\.


--
-- Data for Name: service_categories; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."service_categories" ("id", "name", "is_active", "created_at", "updated_at") FROM stdin;
7855dcb6-7112-4521-b188-0f71d67a7ca6	Sepatu	t	2025-11-25 14:33:09	2025-11-25 14:33:09
1f495ece-0988-4f56-8340-5746acc3e0e1	Tas	t	2025-11-25 14:33:10	2025-11-25 14:33:10
90c95dcb-4e07-4ded-9754-ea8dbac4a2c0	Dompet	t	2025-11-25 14:33:19	2025-11-25 14:33:19
\.


--
-- Data for Name: service_prices; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."service_prices" ("id", "service_id", "branch_id", "price", "created_at", "updated_at") FROM stdin;
\.


--
-- Data for Name: services; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."services" ("id", "category_id", "name", "unit", "price_default", "is_active", "created_at", "updated_at") FROM stdin;
fde10d28-7dc9-4ffc-8630-b71e75b85345	7855dcb6-7112-4521-b188-0f71d67a7ca6	Deep Clean	ITEM	50000.00	t	2025-11-25 14:33:57	2025-11-25 14:33:57
2f310fc1-a7ca-4b4e-88ff-391392b35215	1f495ece-0988-4f56-8340-5746acc3e0e1	Bag Clean	ITEM	125000.00	t	2025-11-25 14:35:22	2025-11-25 14:35:22
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."sessions" ("id", "user_id", "ip_address", "user_agent", "payload", "last_activity") FROM stdin;
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."users" ("id", "name", "email", "email_verified_at", "password", "remember_token", "created_at", "updated_at", "is_active", "branch_id") FROM stdin;
1	Superadmin	superadmin@gmail.com	\N	$2y$12$XbBsF/N8jd2xCFnPnavMvuwVJ4bo7gkL0KjxbfNyrgu4gT0lSNHQq	\N	2025-11-25 14:29:33	2025-11-25 14:29:33	t	\N
2	Admin Cabang	admincabang@gmail.com	\N	$2y$12$fM7ladlS0IiQc8zwcObkcOU2VoM5RRcXJq1Ir3qJ9.PheQWq/wPwy	\N	2025-11-25 14:29:33	2025-11-25 14:29:33	t	71adee1b-91d7-43cc-a712-9eaac873c6a5
3	Kasir	kasir@gmail.com	\N	$2y$12$nSxty7PQT4znogW351TdjOD0z4K6CWnclNuCB7qMtFCkP2m1cuOZK	\N	2025-11-25 14:29:33	2025-11-25 14:29:33	t	71adee1b-91d7-43cc-a712-9eaac873c6a5
4	Petugas Cuci	petugascuci@gmail.com	\N	$2y$12$rx4se8DkoqdSNzXVToFggef5vnUr5K22LT4J.aZ/o3jxJ4Mw5I.Ue	\N	2025-11-25 14:29:33	2025-11-25 14:29:33	t	71adee1b-91d7-43cc-a712-9eaac873c6a5
5	Kurir	kurir@gmail.com	\N	$2y$12$IHhqywfyAqaKN3FN.bCHZ.y3/SZBL1x5hOfLXibpaF85NgyrmRR3.	\N	2025-11-25 14:29:34	2025-11-25 14:29:34	t	71adee1b-91d7-43cc-a712-9eaac873c6a5
\.


--
-- Data for Name: vouchers; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."vouchers" ("id", "branch_id", "code", "type", "value", "start_at", "end_at", "min_total", "usage_limit", "active", "created_at", "updated_at") FROM stdin;
\.


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('"public"."failed_jobs_id_seq"', 1, false);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('"public"."jobs_id_seq"', 1, false);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('"public"."migrations_id_seq"', 29, true);


--
-- Name: permissions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('"public"."permissions_id_seq"', 1, false);


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('"public"."personal_access_tokens_id_seq"', 9, true);


--
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('"public"."roles_id_seq"', 5, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('"public"."users_id_seq"', 5, true);


--
-- Name: branches branches_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."branches"
    ADD CONSTRAINT "branches_code_unique" UNIQUE ("code");


--
-- Name: branches branches_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."branches"
    ADD CONSTRAINT "branches_pkey" PRIMARY KEY ("id");


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."cache_locks"
    ADD CONSTRAINT "cache_locks_pkey" PRIMARY KEY ("key");


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."cache"
    ADD CONSTRAINT "cache_pkey" PRIMARY KEY ("key");


--
-- Name: customers customers_branch_wa_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."customers"
    ADD CONSTRAINT "customers_branch_wa_unique" UNIQUE ("branch_id", "whatsapp");


--
-- Name: customers customers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."customers"
    ADD CONSTRAINT "customers_pkey" PRIMARY KEY ("id");


--
-- Name: deliveries deliveries_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."deliveries"
    ADD CONSTRAINT "deliveries_pkey" PRIMARY KEY ("id");


--
-- Name: delivery_events delivery_events_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."delivery_events"
    ADD CONSTRAINT "delivery_events_pkey" PRIMARY KEY ("id");


--
-- Name: expenses expenses_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."expenses"
    ADD CONSTRAINT "expenses_pkey" PRIMARY KEY ("id");


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."failed_jobs"
    ADD CONSTRAINT "failed_jobs_pkey" PRIMARY KEY ("id");


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."failed_jobs"
    ADD CONSTRAINT "failed_jobs_uuid_unique" UNIQUE ("uuid");


--
-- Name: invoice_counters invoice_counters_branch_id_prefix_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."invoice_counters"
    ADD CONSTRAINT "invoice_counters_branch_id_prefix_unique" UNIQUE ("branch_id", "prefix");


--
-- Name: invoice_counters invoice_counters_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."invoice_counters"
    ADD CONSTRAINT "invoice_counters_pkey" PRIMARY KEY ("id");


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."job_batches"
    ADD CONSTRAINT "job_batches_pkey" PRIMARY KEY ("id");


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."jobs"
    ADD CONSTRAINT "jobs_pkey" PRIMARY KEY ("id");


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."migrations"
    ADD CONSTRAINT "migrations_pkey" PRIMARY KEY ("id");


--
-- Name: model_has_permissions model_has_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."model_has_permissions"
    ADD CONSTRAINT "model_has_permissions_pkey" PRIMARY KEY ("permission_id", "model_id", "model_type");


--
-- Name: model_has_roles model_has_roles_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."model_has_roles"
    ADD CONSTRAINT "model_has_roles_pkey" PRIMARY KEY ("role_id", "model_id", "model_type");


--
-- Name: order_items order_items_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."order_items"
    ADD CONSTRAINT "order_items_pkey" PRIMARY KEY ("id");


--
-- Name: order_photos order_photos_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."order_photos"
    ADD CONSTRAINT "order_photos_pkey" PRIMARY KEY ("id");


--
-- Name: order_vouchers order_vouchers_order_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."order_vouchers"
    ADD CONSTRAINT "order_vouchers_order_id_unique" UNIQUE ("order_id");


--
-- Name: order_vouchers order_vouchers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."order_vouchers"
    ADD CONSTRAINT "order_vouchers_pkey" PRIMARY KEY ("id");


--
-- Name: orders orders_branch_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."orders"
    ADD CONSTRAINT "orders_branch_number_unique" UNIQUE ("branch_id", "number");


--
-- Name: orders orders_invoice_no_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."orders"
    ADD CONSTRAINT "orders_invoice_no_unique" UNIQUE ("invoice_no");


--
-- Name: orders orders_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."orders"
    ADD CONSTRAINT "orders_pkey" PRIMARY KEY ("id");


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."password_reset_tokens"
    ADD CONSTRAINT "password_reset_tokens_pkey" PRIMARY KEY ("email");


--
-- Name: payments payments_order_id_method_amount_paid_at_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."payments"
    ADD CONSTRAINT "payments_order_id_method_amount_paid_at_unique" UNIQUE ("order_id", "method", "amount", "paid_at");


--
-- Name: payments payments_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."payments"
    ADD CONSTRAINT "payments_pkey" PRIMARY KEY ("id");


--
-- Name: permissions permissions_name_guard_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."permissions"
    ADD CONSTRAINT "permissions_name_guard_name_unique" UNIQUE ("name", "guard_name");


--
-- Name: permissions permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."permissions"
    ADD CONSTRAINT "permissions_pkey" PRIMARY KEY ("id");


--
-- Name: personal_access_tokens personal_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."personal_access_tokens"
    ADD CONSTRAINT "personal_access_tokens_pkey" PRIMARY KEY ("id");


--
-- Name: personal_access_tokens personal_access_tokens_token_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."personal_access_tokens"
    ADD CONSTRAINT "personal_access_tokens_token_unique" UNIQUE ("token");


--
-- Name: receivables receivables_order_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."receivables"
    ADD CONSTRAINT "receivables_order_id_unique" UNIQUE ("order_id");


--
-- Name: receivables receivables_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."receivables"
    ADD CONSTRAINT "receivables_pkey" PRIMARY KEY ("id");


--
-- Name: role_has_permissions role_has_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."role_has_permissions"
    ADD CONSTRAINT "role_has_permissions_pkey" PRIMARY KEY ("permission_id", "role_id");


--
-- Name: roles roles_name_guard_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."roles"
    ADD CONSTRAINT "roles_name_guard_name_unique" UNIQUE ("name", "guard_name");


--
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."roles"
    ADD CONSTRAINT "roles_pkey" PRIMARY KEY ("id");


--
-- Name: service_categories service_categories_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."service_categories"
    ADD CONSTRAINT "service_categories_pkey" PRIMARY KEY ("id");


--
-- Name: service_prices service_prices_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."service_prices"
    ADD CONSTRAINT "service_prices_pkey" PRIMARY KEY ("id");


--
-- Name: service_prices service_prices_service_id_branch_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."service_prices"
    ADD CONSTRAINT "service_prices_service_id_branch_id_unique" UNIQUE ("service_id", "branch_id");


--
-- Name: services services_category_id_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."services"
    ADD CONSTRAINT "services_category_id_name_unique" UNIQUE ("category_id", "name");


--
-- Name: services services_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."services"
    ADD CONSTRAINT "services_pkey" PRIMARY KEY ("id");


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."sessions"
    ADD CONSTRAINT "sessions_pkey" PRIMARY KEY ("id");


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."users"
    ADD CONSTRAINT "users_email_unique" UNIQUE ("email");


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."users"
    ADD CONSTRAINT "users_pkey" PRIMARY KEY ("id");


--
-- Name: vouchers vouchers_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."vouchers"
    ADD CONSTRAINT "vouchers_code_unique" UNIQUE ("code");


--
-- Name: vouchers vouchers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."vouchers"
    ADD CONSTRAINT "vouchers_pkey" PRIMARY KEY ("id");


--
-- Name: customers_whatsapp_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "customers_whatsapp_index" ON "public"."customers" USING "btree" ("whatsapp");


--
-- Name: deliveries_assigned_to_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "deliveries_assigned_to_index" ON "public"."deliveries" USING "btree" ("assigned_to");


--
-- Name: deliveries_order_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "deliveries_order_id_index" ON "public"."deliveries" USING "btree" ("order_id");


--
-- Name: delivery_events_delivery_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "delivery_events_delivery_id_index" ON "public"."delivery_events" USING "btree" ("delivery_id");


--
-- Name: expenses_branch_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "expenses_branch_id_index" ON "public"."expenses" USING "btree" ("branch_id");


--
-- Name: idx_deliveries_created; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "idx_deliveries_created" ON "public"."deliveries" USING "btree" ("created_at");


--
-- Name: idx_deliveries_order; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "idx_deliveries_order" ON "public"."deliveries" USING "btree" ("order_id");


--
-- Name: idx_expenses_branch_created; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "idx_expenses_branch_created" ON "public"."expenses" USING "btree" ("branch_id", "created_at");


--
-- Name: idx_order_items_order; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "idx_order_items_order" ON "public"."order_items" USING "btree" ("order_id");


--
-- Name: idx_order_items_service; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "idx_order_items_service" ON "public"."order_items" USING "btree" ("service_id");


--
-- Name: idx_order_vouchers_applied_at; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "idx_order_vouchers_applied_at" ON "public"."order_vouchers" USING "btree" ("applied_at");


--
-- Name: idx_order_vouchers_order; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "idx_order_vouchers_order" ON "public"."order_vouchers" USING "btree" ("order_id");


--
-- Name: idx_orders_branch_created; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "idx_orders_branch_created" ON "public"."orders" USING "btree" ("branch_id", "created_at");


--
-- Name: idx_orders_paid_at; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "idx_orders_paid_at" ON "public"."orders" USING "btree" ("paid_at");


--
-- Name: idx_payments_order; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "idx_payments_order" ON "public"."payments" USING "btree" ("order_id");


--
-- Name: idx_payments_paid_at; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "idx_payments_paid_at" ON "public"."payments" USING "btree" ("paid_at");


--
-- Name: idx_receivables_order; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "idx_receivables_order" ON "public"."receivables" USING "btree" ("order_id");


--
-- Name: idx_receivables_status_due; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "idx_receivables_status_due" ON "public"."receivables" USING "btree" ("status", "due_date");


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "jobs_queue_index" ON "public"."jobs" USING "btree" ("queue");


--
-- Name: model_has_permissions_model_id_model_type_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "model_has_permissions_model_id_model_type_index" ON "public"."model_has_permissions" USING "btree" ("model_id", "model_type");


--
-- Name: model_has_roles_model_id_model_type_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "model_has_roles_model_id_model_type_index" ON "public"."model_has_roles" USING "btree" ("model_id", "model_type");


--
-- Name: order_items_order_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "order_items_order_id_index" ON "public"."order_items" USING "btree" ("order_id");


--
-- Name: order_photos_kind_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "order_photos_kind_index" ON "public"."order_photos" USING "btree" ("kind");


--
-- Name: order_photos_order_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "order_photos_order_id_index" ON "public"."order_photos" USING "btree" ("order_id");


--
-- Name: order_vouchers_voucher_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "order_vouchers_voucher_id_index" ON "public"."order_vouchers" USING "btree" ("voucher_id");


--
-- Name: orders_created_by_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "orders_created_by_index" ON "public"."orders" USING "btree" ("created_by");


--
-- Name: orders_paid_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "orders_paid_at_index" ON "public"."orders" USING "btree" ("paid_at");


--
-- Name: orders_payment_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "orders_payment_status_index" ON "public"."orders" USING "btree" ("payment_status");


--
-- Name: orders_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "orders_status_index" ON "public"."orders" USING "btree" ("status");


--
-- Name: payments_order_id_method_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "payments_order_id_method_index" ON "public"."payments" USING "btree" ("order_id", "method");


--
-- Name: personal_access_tokens_expires_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "personal_access_tokens_expires_at_index" ON "public"."personal_access_tokens" USING "btree" ("expires_at");


--
-- Name: personal_access_tokens_tokenable_type_tokenable_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "personal_access_tokens_tokenable_type_tokenable_id_index" ON "public"."personal_access_tokens" USING "btree" ("tokenable_type", "tokenable_id");


--
-- Name: receivables_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "receivables_status_index" ON "public"."receivables" USING "btree" ("status");


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "sessions_last_activity_index" ON "public"."sessions" USING "btree" ("last_activity");


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "sessions_user_id_index" ON "public"."sessions" USING "btree" ("user_id");


--
-- Name: vouchers_branch_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "vouchers_branch_id_index" ON "public"."vouchers" USING "btree" ("branch_id");


--
-- Name: customers customers_branch_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."customers"
    ADD CONSTRAINT "customers_branch_id_foreign" FOREIGN KEY ("branch_id") REFERENCES "public"."branches"("id") ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: deliveries deliveries_assigned_to_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."deliveries"
    ADD CONSTRAINT "deliveries_assigned_to_foreign" FOREIGN KEY ("assigned_to") REFERENCES "public"."users"("id") ON DELETE SET NULL;


--
-- Name: deliveries deliveries_order_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."deliveries"
    ADD CONSTRAINT "deliveries_order_id_foreign" FOREIGN KEY ("order_id") REFERENCES "public"."orders"("id") ON DELETE CASCADE;


--
-- Name: delivery_events delivery_events_delivery_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."delivery_events"
    ADD CONSTRAINT "delivery_events_delivery_id_foreign" FOREIGN KEY ("delivery_id") REFERENCES "public"."deliveries"("id") ON DELETE CASCADE;


--
-- Name: expenses expenses_branch_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."expenses"
    ADD CONSTRAINT "expenses_branch_id_foreign" FOREIGN KEY ("branch_id") REFERENCES "public"."branches"("id") ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: invoice_counters invoice_counters_branch_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."invoice_counters"
    ADD CONSTRAINT "invoice_counters_branch_id_foreign" FOREIGN KEY ("branch_id") REFERENCES "public"."branches"("id") ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: model_has_permissions model_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."model_has_permissions"
    ADD CONSTRAINT "model_has_permissions_permission_id_foreign" FOREIGN KEY ("permission_id") REFERENCES "public"."permissions"("id") ON DELETE CASCADE;


--
-- Name: model_has_roles model_has_roles_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."model_has_roles"
    ADD CONSTRAINT "model_has_roles_role_id_foreign" FOREIGN KEY ("role_id") REFERENCES "public"."roles"("id") ON DELETE CASCADE;


--
-- Name: order_items order_items_order_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."order_items"
    ADD CONSTRAINT "order_items_order_id_foreign" FOREIGN KEY ("order_id") REFERENCES "public"."orders"("id") ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: order_items order_items_service_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."order_items"
    ADD CONSTRAINT "order_items_service_id_foreign" FOREIGN KEY ("service_id") REFERENCES "public"."services"("id") ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: order_photos order_photos_order_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."order_photos"
    ADD CONSTRAINT "order_photos_order_id_foreign" FOREIGN KEY ("order_id") REFERENCES "public"."orders"("id") ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: order_vouchers order_vouchers_applied_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."order_vouchers"
    ADD CONSTRAINT "order_vouchers_applied_by_foreign" FOREIGN KEY ("applied_by") REFERENCES "public"."users"("id") ON UPDATE CASCADE ON DELETE SET NULL;


--
-- Name: order_vouchers order_vouchers_order_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."order_vouchers"
    ADD CONSTRAINT "order_vouchers_order_id_foreign" FOREIGN KEY ("order_id") REFERENCES "public"."orders"("id") ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: order_vouchers order_vouchers_voucher_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."order_vouchers"
    ADD CONSTRAINT "order_vouchers_voucher_id_foreign" FOREIGN KEY ("voucher_id") REFERENCES "public"."vouchers"("id") ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: orders orders_branch_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."orders"
    ADD CONSTRAINT "orders_branch_id_foreign" FOREIGN KEY ("branch_id") REFERENCES "public"."branches"("id") ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: orders orders_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."orders"
    ADD CONSTRAINT "orders_created_by_foreign" FOREIGN KEY ("created_by") REFERENCES "public"."users"("id") ON UPDATE CASCADE ON DELETE SET NULL;


--
-- Name: orders orders_customer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."orders"
    ADD CONSTRAINT "orders_customer_id_foreign" FOREIGN KEY ("customer_id") REFERENCES "public"."customers"("id") ON UPDATE CASCADE ON DELETE SET NULL;


--
-- Name: payments payments_order_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."payments"
    ADD CONSTRAINT "payments_order_id_foreign" FOREIGN KEY ("order_id") REFERENCES "public"."orders"("id") ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: receivables receivables_order_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."receivables"
    ADD CONSTRAINT "receivables_order_id_foreign" FOREIGN KEY ("order_id") REFERENCES "public"."orders"("id") ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: role_has_permissions role_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."role_has_permissions"
    ADD CONSTRAINT "role_has_permissions_permission_id_foreign" FOREIGN KEY ("permission_id") REFERENCES "public"."permissions"("id") ON DELETE CASCADE;


--
-- Name: role_has_permissions role_has_permissions_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."role_has_permissions"
    ADD CONSTRAINT "role_has_permissions_role_id_foreign" FOREIGN KEY ("role_id") REFERENCES "public"."roles"("id") ON DELETE CASCADE;


--
-- Name: service_prices service_prices_branch_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."service_prices"
    ADD CONSTRAINT "service_prices_branch_id_foreign" FOREIGN KEY ("branch_id") REFERENCES "public"."branches"("id") ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: service_prices service_prices_service_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."service_prices"
    ADD CONSTRAINT "service_prices_service_id_foreign" FOREIGN KEY ("service_id") REFERENCES "public"."services"("id") ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: services services_category_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."services"
    ADD CONSTRAINT "services_category_id_foreign" FOREIGN KEY ("category_id") REFERENCES "public"."service_categories"("id") ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: users users_branch_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."users"
    ADD CONSTRAINT "users_branch_id_foreign" FOREIGN KEY ("branch_id") REFERENCES "public"."branches"("id") ON UPDATE CASCADE ON DELETE SET NULL;


--
-- Name: vouchers vouchers_branch_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."vouchers"
    ADD CONSTRAINT "vouchers_branch_id_foreign" FOREIGN KEY ("branch_id") REFERENCES "public"."branches"("id") ON UPDATE CASCADE ON DELETE SET NULL;


--
-- PostgreSQL database dump complete
--

\unrestrict vp14ClUYuwHtyC5n5DV8Dq1TIOZdbJXJNouRcAMk07x90yxJ4liOTWe5yTCa0mV

