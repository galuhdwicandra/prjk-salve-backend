--
-- PostgreSQL database dump
--

\restrict RR0bwcPd4aZJtm3d9iLKS1g8sCZQFxq4LxFpa5FuAPCQAn9O6PV6nHGPsKE0s2V

-- Dumped from database version 16.11 (Ubuntu 16.11-0ubuntu0.24.04.1)
-- Dumped by pg_dump version 16.11 (Ubuntu 16.11-0ubuntu0.24.04.1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: public; Type: SCHEMA; Schema: -; Owner: -
--

-- *not* creating schema, since initdb creates it


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
-- Name: loyalty_accounts; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "public"."loyalty_accounts" (
    "id" "uuid" NOT NULL,
    "customer_id" "uuid" NOT NULL,
    "branch_id" "uuid" NOT NULL,
    "stamps" smallint DEFAULT '0'::smallint NOT NULL,
    "lifetime" integer DEFAULT 0 NOT NULL,
    "created_at" timestamp(0) without time zone,
    "updated_at" timestamp(0) without time zone
);


--
-- Name: loyalty_logs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "public"."loyalty_logs" (
    "id" "uuid" NOT NULL,
    "order_id" "uuid",
    "customer_id" "uuid" NOT NULL,
    "branch_id" "uuid" NOT NULL,
    "action" character varying(20) NOT NULL,
    "before" smallint DEFAULT '0'::smallint NOT NULL,
    "after" smallint DEFAULT '0'::smallint NOT NULL,
    "created_at" timestamp(0) without time zone,
    "updated_at" timestamp(0) without time zone
);


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
    "created_by" bigint,
    "received_at" timestamp(0) without time zone,
    "ready_at" timestamp(0) without time zone,
    "loyalty_reward" character varying(16) DEFAULT 'NONE'::character varying NOT NULL,
    "loyalty_discount" numeric(12,2) DEFAULT '0'::numeric NOT NULL
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
    "branch_id" "uuid",
    "username" character varying(50)
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
-- Name: wash_note_items; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "public"."wash_note_items" (
    "id" "uuid" NOT NULL,
    "wash_note_id" "uuid" NOT NULL,
    "order_id" "uuid" NOT NULL,
    "qty" numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    "process_status" character varying(20),
    "started_at" time(0) without time zone,
    "finished_at" time(0) without time zone,
    "note" character varying(200),
    "created_at" timestamp(0) without time zone,
    "updated_at" timestamp(0) without time zone
);


--
-- Name: wash_notes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "public"."wash_notes" (
    "id" "uuid" NOT NULL,
    "user_id" bigint NOT NULL,
    "branch_id" "uuid",
    "note_date" "date" NOT NULL,
    "orders_count" integer DEFAULT 0 NOT NULL,
    "total_qty" numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    "created_at" timestamp(0) without time zone,
    "updated_at" timestamp(0) without time zone
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
61435590-df0a-4857-8432-b5eb94fe40a7	CBG-002	Cabang Kedua	Ujung Berung	SLV	never	2026-02-12 19:37:54	2026-02-12 19:37:54
739948f1-5f86-41d6-862f-24dc94b12e1f	CBG-001	Cabang Utama	Alamat Cabang Utama	SLV	never	2025-12-17 17:16:24	2026-02-12 19:37:59
\.


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."cache" ("key", "value", "expiration") FROM stdin;
salve-cache-spatie.permission.cache	a:3:{s:5:"alias";a:0:{}s:11:"permissions";a:0:{}s:5:"roles";a:0:{}}	1770987105
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
1a360d41-5058-4c94-8f6c-f911b02d2103	739948f1-5f86-41d6-862f-24dc94b12e1f	galuh	081214695222	Permata Biru Blok AR.06	\N	2026-01-04 20:58:19+07	2026-01-04 20:58:19+07
5290def6-017c-4430-9452-4603b1682ddf	739948f1-5f86-41d6-862f-24dc94b12e1f	orang 1	089758542511	komplek permata biru blok yz	\N	2026-02-11 17:49:20+07	2026-02-11 17:49:20+07
\.


--
-- Data for Name: deliveries; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."deliveries" ("id", "order_id", "type", "zone_id", "fee", "assigned_to", "auto_assigned", "status", "handover_photo", "created_at", "updated_at") FROM stdin;
019c5142-8537-7385-afcf-8879b57b43a6	019c4ccf-4c2b-70a2-ad23-1f02ca8a8980	delivery	\N	5000.00	5	t	PICKED	\N	2026-02-12 16:50:39	2026-02-12 16:58:16
\.


--
-- Data for Name: delivery_events; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."delivery_events" ("id", "delivery_id", "status", "note", "created_at", "updated_at") FROM stdin;
019c5142-854c-73e7-bde4-809780c74333	019c5142-8537-7385-afcf-8879b57b43a6	CREATED	Delivery created	2026-02-12 16:50:39	2026-02-12 16:50:39
019c5142-8566-7062-825b-0a3cfc34683d	019c5142-8537-7385-afcf-8879b57b43a6	ASSIGNED	Auto-assigned courier #5	2026-02-12 16:50:39	2026-02-12 16:50:39
019c5149-5d6d-7233-98f7-338c3e438bbe	019c5142-8537-7385-afcf-8879b57b43a6	ON_THE_WAY	\N	2026-02-12 16:58:08	2026-02-12 16:58:08
019c5149-7d75-71eb-b342-06d870e2698c	019c5142-8537-7385-afcf-8879b57b43a6	PICKED	\N	2026-02-12 16:58:16	2026-02-12 16:58:16
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
019b894d-a983-70ec-87b1-5707ec6ad3c0	739948f1-5f86-41d6-862f-24dc94b12e1f	SLV	1	monthly	202602	2026-01-04 20:58:46	2026-02-11 20:06:19
019c51db-e02f-7185-b89e-050223693767	61435590-df0a-4857-8432-b5eb94fe40a7	SLV	0	never	\N	2026-02-12 19:38:10	2026-02-12 19:38:18
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
-- Data for Name: loyalty_accounts; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."loyalty_accounts" ("id", "customer_id", "branch_id", "stamps", "lifetime", "created_at", "updated_at") FROM stdin;
019c4c51-e2b4-732b-8ec3-056a6f575d55	5290def6-017c-4430-9452-4603b1682ddf	739948f1-5f86-41d6-862f-24dc94b12e1f	0	0	2026-02-11 17:49:20	2026-02-11 17:49:20
019b894d-69db-72b8-a6a8-2da8792903bf	1a360d41-5058-4c94-8f6c-f911b02d2103	739948f1-5f86-41d6-862f-24dc94b12e1f	7	7	2026-01-04 20:58:30	2026-02-11 20:06:19
\.


--
-- Data for Name: loyalty_logs; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."loyalty_logs" ("id", "order_id", "customer_id", "branch_id", "action", "before", "after", "created_at", "updated_at") FROM stdin;
019b894d-a997-706a-b8f7-1b62a371c920	019b894d-a986-70ec-98cc-d53cace37cdc	1a360d41-5058-4c94-8f6c-f911b02d2103	739948f1-5f86-41d6-862f-24dc94b12e1f	EARN	0	1	2026-01-04 20:58:46	2026-01-04 20:58:46
019b894e-4240-70b3-b16c-0b4a7f0ae195	019b894e-4231-705e-b336-0ff1f12599de	1a360d41-5058-4c94-8f6c-f911b02d2103	739948f1-5f86-41d6-862f-24dc94b12e1f	EARN	1	2	2026-01-04 20:59:26	2026-01-04 20:59:26
019b8953-d42b-739d-a7a1-b682595e6758	019b8953-d41c-7055-8b55-8248b1b97bf9	1a360d41-5058-4c94-8f6c-f911b02d2103	739948f1-5f86-41d6-862f-24dc94b12e1f	EARN	2	3	2026-01-04 21:05:31	2026-01-04 21:05:31
019b8990-621e-7203-bc0f-7b119ab8c43a	019b8990-6184-73eb-92a9-380026865da4	1a360d41-5058-4c94-8f6c-f911b02d2103	739948f1-5f86-41d6-862f-24dc94b12e1f	EARN	3	4	2026-01-04 22:11:39	2026-01-04 22:11:39
019b899e-912b-73dd-9f3d-86546cc8a1f1	\N	1a360d41-5058-4c94-8f6c-f911b02d2103	739948f1-5f86-41d6-862f-24dc94b12e1f	REWARD25	4	4	2026-01-04 22:27:09	2026-01-04 22:27:09
019b899e-912d-7126-a717-afe10779e0f5	019b899e-9119-70c7-a992-695eb8e0a6f9	1a360d41-5058-4c94-8f6c-f911b02d2103	739948f1-5f86-41d6-862f-24dc94b12e1f	EARN	4	5	2026-01-04 22:27:09	2026-01-04 22:27:09
019b89a5-7401-71b3-a4fa-c803246b9f9f	019b89a5-73ee-71fb-a58f-e1fb4809c8ba	1a360d41-5058-4c94-8f6c-f911b02d2103	739948f1-5f86-41d6-862f-24dc94b12e1f	EARN	5	6	2026-01-04 22:34:40	2026-01-04 22:34:40
019c4ccf-4c4e-7111-b7e7-37ddca9cd761	019c4ccf-4c2b-70a2-ad23-1f02ca8a8980	1a360d41-5058-4c94-8f6c-f911b02d2103	739948f1-5f86-41d6-862f-24dc94b12e1f	EARN	6	7	2026-02-11 20:06:19	2026-02-11 20:06:19
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
29	2025_11_25_145010_fix_orders_created_by_to_bigint	1
30	2025_12_04_224859_add_dates_to_orders_table	1
31	2025_12_05_004843_add_username_to_users_table	1
32	2025_12_05_134121_create_loyalty_accounts_table	1
33	2025_12_05_134150_create_loyalty_logs_table	1
34	2025_12_05_134214_alter_orders_add_loyalty_columns	1
35	2025_12_07_160309_create_wash_notes_tables	1
36	2025_12_09_154416_add_unique_order_id_to_wash_note_items	1
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
2	App\\Models\\User	2
5	App\\Models\\User	5
4	App\\Models\\User	4
1	App\\Models\\User	1
3	App\\Models\\User	6
3	App\\Models\\User	3
2	App\\Models\\User	7
3	App\\Models\\User	8
\.


--
-- Data for Name: order_items; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."order_items" ("id", "order_id", "service_id", "qty", "price", "total", "note", "created_at", "updated_at") FROM stdin;
019b894d-a98b-71c1-8333-e2f4767c5f95	019b894d-a986-70ec-98cc-d53cace37cdc	f0ca95aa-1aa7-4681-ab36-556c70e5f582	1.00	125000.00	125000.00	\N	2026-01-04 20:58:46	2026-01-04 20:58:46
019b894d-a98e-73b2-ab40-e21248d888d3	019b894d-a986-70ec-98cc-d53cace37cdc	ca5a6897-aa04-4c40-8f3a-28c9947ffb61	1.00	50000.00	50000.00	\N	2026-01-04 20:58:46	2026-01-04 20:58:46
019b894e-4236-7151-b543-b6c08a30de2b	019b894e-4231-705e-b336-0ff1f12599de	f0ca95aa-1aa7-4681-ab36-556c70e5f582	1.00	125000.00	125000.00	\N	2026-01-04 20:59:26	2026-01-04 20:59:26
019b894e-4239-71a1-9bb1-b0ee94bfbc27	019b894e-4231-705e-b336-0ff1f12599de	ca5a6897-aa04-4c40-8f3a-28c9947ffb61	1.00	50000.00	50000.00	\N	2026-01-04 20:59:26	2026-01-04 20:59:26
019b8953-d422-7155-83ed-0bff8e811810	019b8953-d41c-7055-8b55-8248b1b97bf9	f0ca95aa-1aa7-4681-ab36-556c70e5f582	1.00	125000.00	125000.00	\N	2026-01-04 21:05:31	2026-01-04 21:05:31
019b8953-d424-7107-bb00-c5bf32b28c76	019b8953-d41c-7055-8b55-8248b1b97bf9	ca5a6897-aa04-4c40-8f3a-28c9947ffb61	1.00	50000.00	50000.00	\N	2026-01-04 21:05:31	2026-01-04 21:05:31
019b8990-61bb-7118-98f2-c024eb97ea32	019b8990-6184-73eb-92a9-380026865da4	ca5a6897-aa04-4c40-8f3a-28c9947ffb61	3.00	50000.00	150000.00	\N	2026-01-04 22:11:39	2026-01-04 22:11:39
019b8990-61c7-71ba-b990-37540fd00c69	019b8990-6184-73eb-92a9-380026865da4	f0ca95aa-1aa7-4681-ab36-556c70e5f582	1.00	125000.00	125000.00	\N	2026-01-04 22:11:39	2026-01-04 22:11:39
019b899e-9120-7019-9fca-7a561352aa50	019b899e-9119-70c7-a992-695eb8e0a6f9	ca5a6897-aa04-4c40-8f3a-28c9947ffb61	2.00	50000.00	100000.00	\N	2026-01-04 22:27:09	2026-01-04 22:27:09
019b899e-9124-724e-88d5-7e7f9c9ab837	019b899e-9119-70c7-a992-695eb8e0a6f9	f0ca95aa-1aa7-4681-ab36-556c70e5f582	2.00	125000.00	250000.00	\N	2026-01-04 22:27:09	2026-01-04 22:27:09
019b89a5-73f7-70df-af5e-7265c34d038c	019b89a5-73ee-71fb-a58f-e1fb4809c8ba	f0ca95aa-1aa7-4681-ab36-556c70e5f582	1.00	125000.00	125000.00	\N	2026-01-04 22:34:40	2026-01-04 22:34:40
019b89a5-73f9-7190-b313-898286ae47e0	019b89a5-73ee-71fb-a58f-e1fb4809c8ba	ca5a6897-aa04-4c40-8f3a-28c9947ffb61	1.00	50000.00	50000.00	\N	2026-01-04 22:34:40	2026-01-04 22:34:40
019c4cfd-a0e8-7236-a22f-1e140063e67a	019c4ccf-4c2b-70a2-ad23-1f02ca8a8980	f0ca95aa-1aa7-4681-ab36-556c70e5f582	2.00	125000.00	250000.00	\N	2026-02-11 20:56:56	2026-02-11 20:56:56
\.


--
-- Data for Name: order_photos; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."order_photos" ("id", "order_id", "kind", "path", "created_at", "updated_at") FROM stdin;
019c4d40-1393-7214-b7b9-5fab11e6fc42	019c4ccf-4c2b-70a2-ad23-1f02ca8a8980	before	storage/uploads/orders/019c4ccf-4c2b-70a2-ad23-1f02ca8a8980/before/Jk7ubbpNqy1kE8TDhxrccVKdlDz0nnHWdzE86tz9.png	2026-02-11 22:09:30	2026-02-11 22:09:30
019c4d40-3601-7321-9e6a-97bba23cac67	019c4ccf-4c2b-70a2-ad23-1f02ca8a8980	after	storage/uploads/orders/019c4ccf-4c2b-70a2-ad23-1f02ca8a8980/after/yw2Pcbd8D37WSxByWVnA4ohOz9Mw9lp4K4MqP6UD.jpg	2026-02-11 22:09:39	2026-02-11 22:09:39
019c4d47-a887-735f-990e-ef317830f8d6	019c4ccf-4c2b-70a2-ad23-1f02ca8a8980	before	storage/uploads/orders/019c4ccf-4c2b-70a2-ad23-1f02ca8a8980/before/u9VFGUCd42YzWysj0HBfHqwyCbRPVzgcYetpkFED.jpg	2026-02-11 22:17:47	2026-02-11 22:17:47
\.


--
-- Data for Name: order_vouchers; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."order_vouchers" ("id", "order_id", "voucher_id", "applied_amount", "applied_by", "applied_at", "created_at", "updated_at") FROM stdin;
\.


--
-- Data for Name: orders; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."orders" ("id", "branch_id", "customer_id", "number", "status", "subtotal", "discount", "grand_total", "paid_amount", "due_amount", "notes", "created_at", "updated_at", "payment_status", "dp_amount", "paid_at", "invoice_no", "created_by", "received_at", "ready_at", "loyalty_reward", "loyalty_discount") FROM stdin;
019b894d-a986-70ec-98cc-d53cace37cdc	739948f1-5f86-41d6-862f-24dc94b12e1f	1a360d41-5058-4c94-8f6c-f911b02d2103	SLV-202601-000001	QUEUE	175000.00	0.00	175000.00	0.00	175000.00	\N	2026-01-04 20:58:46	2026-01-04 20:58:46	PENDING	0.00	\N	INV-04-01-0001	3	2026-01-04 20:58:00	2026-01-07 12:30:00	NONE	0.00
019b894e-4231-705e-b336-0ff1f12599de	739948f1-5f86-41d6-862f-24dc94b12e1f	1a360d41-5058-4c94-8f6c-f911b02d2103	SLV-202601-000002	QUEUE	175000.00	0.00	175000.00	0.00	175000.00	\N	2026-01-04 20:59:26	2026-01-04 20:59:26	PENDING	0.00	\N	INV-04-01-0002	3	2026-01-04 20:58:00	2026-01-07 12:30:00	NONE	0.00
019b8953-d41c-7055-8b55-8248b1b97bf9	739948f1-5f86-41d6-862f-24dc94b12e1f	1a360d41-5058-4c94-8f6c-f911b02d2103	SLV-202601-000003	QUEUE	175000.00	0.00	175000.00	0.00	175000.00	\N	2026-01-04 21:05:31	2026-01-04 21:05:31	PENDING	0.00	\N	INV-04-01-0003	3	2026-01-04 20:58:00	2026-01-07 12:30:00	NONE	0.00
019b8990-6184-73eb-92a9-380026865da4	739948f1-5f86-41d6-862f-24dc94b12e1f	1a360d41-5058-4c94-8f6c-f911b02d2103	SLV-202601-000004	QUEUE	275000.00	0.00	275000.00	0.00	275000.00	\N	2026-01-04 22:11:39	2026-01-04 22:11:39	PENDING	0.00	\N	INV-04-01-0004	3	2026-01-04 22:11:00	2026-01-07 16:00:00	NONE	0.00
019b899e-9119-70c7-a992-695eb8e0a6f9	739948f1-5f86-41d6-862f-24dc94b12e1f	1a360d41-5058-4c94-8f6c-f911b02d2103	SLV-202601-000005	QUEUE	350000.00	87500.00	262500.00	262500.00	0.00	\N	2026-01-04 22:27:09	2026-02-11 19:02:54	PAID	80000.00	2026-02-11 05:02:00	INV-04-01-0005	3	2026-01-04 22:26:00	2026-01-08 17:00:00	DISC25	87500.00
019c4ccf-4c2b-70a2-ad23-1f02ca8a8980	739948f1-5f86-41d6-862f-24dc94b12e1f	1a360d41-5058-4c94-8f6c-f911b02d2103	SLV-202602-000001	DRYING	250000.00	0.00	250000.00	50000.00	200000.00	\N	2026-02-11 20:06:19	2026-02-11 22:03:21	DP	50000.00	\N	INV-11-02-0001	3	2026-02-11 06:05:00	2026-02-15 07:05:00	NONE	0.00
019b89a5-73ee-71fb-a58f-e1fb4809c8ba	739948f1-5f86-41d6-862f-24dc94b12e1f	1a360d41-5058-4c94-8f6c-f911b02d2103	SLV-202601-000006	WASHING	175000.00	0.00	175000.00	175000.00	0.00	\N	2026-01-04 22:34:40	2026-02-11 22:16:24	PAID	0.00	2026-01-04 08:47:00	INV-04-01-0006	3	2026-01-04 22:34:00	2026-01-07 19:00:00	NONE	0.00
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
e1e52005-5e1a-4738-8d03-aecf9b4337e4	019b899e-9119-70c7-a992-695eb8e0a6f9	DP	80000.00	2026-01-04 22:27:00+07	\N	2026-01-04 22:27:09+07	2026-01-04 22:27:09+07
ac4e68b7-1a0b-4518-9a7a-fd3bf754bdc6	019b89a5-73ee-71fb-a58f-e1fb4809c8ba	QRIS	175000.00	2026-01-04 08:47:00+07	\N	2026-01-04 22:47:24+07	2026-01-04 22:47:24+07
a7a5d1a1-f818-4477-831a-b27a9c0b9995	019b899e-9119-70c7-a992-695eb8e0a6f9	QRIS	182500.00	2026-02-11 05:02:00+07	\N	2026-02-11 19:02:54+07	2026-02-11 19:02:54+07
e3e0ff59-f7fa-4d5b-86cb-978eabe84032	019c4ccf-4c2b-70a2-ad23-1f02ca8a8980	DP	50000.00	2026-02-11 20:06:00+07	\N	2026-02-11 20:06:20+07	2026-02-11 20:06:20+07
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
55	App\\Models\\User	8	auth-token	f77e45634f692a48a72b764737916b91963e6f29341d6c8f4542e8cc549a22c4	["*"]	2026-02-12 20:56:46	\N	2026-02-12 20:54:05	2026-02-12 20:56:46
\.


--
-- Data for Name: receivables; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."receivables" ("id", "order_id", "remaining_amount", "status", "due_date", "created_at", "updated_at") FROM stdin;
f3d51ac9-2769-4d60-91bf-ea6cdc6dd524	019b894d-a986-70ec-98cc-d53cace37cdc	175000.00	OPEN	\N	2026-01-04 20:58:46+07	2026-01-04 20:58:46+07
35ca0487-4b45-44ef-bfe6-c7485da0f5b4	019b894e-4231-705e-b336-0ff1f12599de	175000.00	OPEN	\N	2026-01-04 20:59:26+07	2026-01-04 20:59:26+07
cda6727e-64d2-4e33-9257-5f284976329a	019b8953-d41c-7055-8b55-8248b1b97bf9	175000.00	OPEN	\N	2026-01-04 21:05:31+07	2026-01-04 21:05:31+07
e49b2972-f331-407b-a31d-e9b4aa8579ba	019b8990-6184-73eb-92a9-380026865da4	275000.00	OPEN	\N	2026-01-04 22:11:39+07	2026-01-04 22:11:39+07
490ba3cb-e7a3-497f-a90b-c5b9ea7f0921	019b89a5-73ee-71fb-a58f-e1fb4809c8ba	0.00	SETTLED	\N	2026-01-04 22:34:40+07	2026-01-04 22:47:24+07
1a426bc5-8c8d-4d36-84a9-4aab6bb95ec3	019b899e-9119-70c7-a992-695eb8e0a6f9	0.00	SETTLED	\N	2026-01-04 22:27:09+07	2026-02-11 19:02:54+07
5f24e75a-8a5d-4829-9f82-539046b0d4cd	019c4ccf-4c2b-70a2-ad23-1f02ca8a8980	200000.00	PARTIAL	\N	2026-02-11 20:06:19+07	2026-02-11 20:56:56+07
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
1	Superadmin	web	2025-12-17 17:16:24	2025-12-17 17:16:24
2	Admin Cabang	web	2025-12-17 17:16:24	2025-12-17 17:16:24
3	Kasir	web	2025-12-17 17:16:24	2025-12-17 17:16:24
4	Petugas Cuci	web	2025-12-17 17:16:24	2025-12-17 17:16:24
5	Kurir	web	2025-12-17 17:16:24	2025-12-17 17:16:24
\.


--
-- Data for Name: service_categories; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."service_categories" ("id", "name", "is_active", "created_at", "updated_at") FROM stdin;
efb22269-37d6-487b-ae22-9257bbccd850	Sepatu	t	2026-01-04 20:56:43	2026-01-04 20:56:43
2de4267e-19e8-4cb6-a528-95f4190d96e4	Tas	t	2026-01-04 20:56:46	2026-01-04 20:56:46
\.


--
-- Data for Name: service_prices; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."service_prices" ("id", "service_id", "branch_id", "price", "created_at", "updated_at") FROM stdin;
019c51dc-9af6-72a6-886e-8dbc131acc39	f0ca95aa-1aa7-4681-ab36-556c70e5f582	61435590-df0a-4857-8432-b5eb94fe40a7	50000.00	2026-02-12 19:38:58	2026-02-12 19:38:58
019c51dc-e470-71e5-b504-5bcebe0d953d	ca5a6897-aa04-4c40-8f3a-28c9947ffb61	61435590-df0a-4857-8432-b5eb94fe40a7	60000.00	2026-02-12 19:39:16	2026-02-12 19:39:16
\.


--
-- Data for Name: services; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."services" ("id", "category_id", "name", "unit", "price_default", "is_active", "created_at", "updated_at") FROM stdin;
ca5a6897-aa04-4c40-8f3a-28c9947ffb61	efb22269-37d6-487b-ae22-9257bbccd850	Deep Clean	PASANG	50000.00	t	2026-01-04 20:57:11	2026-01-04 20:57:11
f0ca95aa-1aa7-4681-ab36-556c70e5f582	2de4267e-19e8-4cb6-a528-95f4190d96e4	Bag Clean	ITEM	125000.00	t	2026-01-04 20:57:34	2026-01-04 20:57:34
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."sessions" ("id", "user_id", "ip_address", "user_agent", "payload", "last_activity") FROM stdin;
EjChwegkkpyMSkBFUFnfGqIDpVYJgOFgYTwC5iKF	\N	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0	YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMVQyejNjSmV1M3BkOFpvbkk2OEc4RVNWTG9QM01UdUZVd2lMbkVrNSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoxNjI6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9yL3JlY2VpcHQvMDE5Yjg5OTAtNjE4NC03M2ViLTkyYTktMzgwMDI2ODY1ZGE0P2V4cGlyZXM9MTc3MDgxODYwMyZzaWduYXR1cmU9NGZlOWY0NTFiZGE0MThhNWNhYWYxMmE0ZmZhYWY4MjEyYmRmNzJjNDg5ZGEyNDZlYzMzNjY4ZTE3NDYwYWFiOSI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjE2MjoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL3IvcmVjZWlwdC8wMTliODk5MC02MTg0LTczZWItOTJhOS0zODAwMjY4NjVkYTQ/ZXhwaXJlcz0xNzcwODE5ODQ4JnNpZ25hdHVyZT1kMWU1NWI3YmQxYmM2M2Y1NGQzMjExOGEyNTZjNGMxYjJmZGVkNjY1NjQ4M2FhMGYwOWIxNzc0YjBkMjE1MWYzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==	1770812659
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."users" ("id", "name", "email", "email_verified_at", "password", "remember_token", "created_at", "updated_at", "is_active", "branch_id", "username") FROM stdin;
2	Admin Cabang	admincabang@gmail.com	\N	$2y$12$1JalhOZ92tCyPaGNMemYheuhMiErOc36X4zEMg3iL6XwobeGOd0ai	\N	2025-12-17 17:16:24	2025-12-17 17:18:04	t	739948f1-5f86-41d6-862f-24dc94b12e1f	admincabang
3	Kasir	kasir@gmail.com	\N	$2y$12$ePJRrjjUGrLi4fbYwaFuR.y/TI4BepZDzl0IhiWfl3NLJqWDmrQ/S	\N	2025-12-17 17:16:24	2025-12-17 17:18:11	t	739948f1-5f86-41d6-862f-24dc94b12e1f	kasir
5	Kurir	kurir@gmail.com	\N	$2y$12$nZ.c4nyx.ISA0zXdJS.0eesFhmfhwqDsVF3OEbMgQkxDiqymC1ypu	\N	2025-12-17 17:16:25	2025-12-17 17:18:17	t	739948f1-5f86-41d6-862f-24dc94b12e1f	kurir
1	Superadmin	superadmin@gmail.com	\N	$2y$12$.tbZFB382Y4m3Fegr5Ffju5lw88X8M397Op2stxeqb0GDZGUHfkW2	\N	2025-12-17 17:16:24	2025-12-17 17:18:34	t	\N	superadmin
4	Petugas Cuci	petugascuci@gmail.com	\N	$2y$12$0jAArtoaVZOB/Cfu3/RSYOWwNm.Sxw/zN9x8QHFeM5bEsjtUl2Hge	\N	2025-12-17 17:16:24	2026-02-11 23:47:08	t	739948f1-5f86-41d6-862f-24dc94b12e1f	petugascuci
6	karyawan2	karyawan2@gmail.com	\N	$2y$12$nOIJkPHClVt8mhGzv/SI5eCsZF7n6ahLSH4cQ6q8Cj3xNcfb7P1yu	\N	2026-02-12 00:01:56	2026-02-12 00:01:56	t	739948f1-5f86-41d6-862f-24dc94b12e1f	karyawan2
7	admin cabang 2	admincabang2@gmail.com	\N	$2y$12$EPiu0idqJVYq6Pm4BxDZfeSpmFgCRdg6pALmXrvFDMozReXhDM7uW	\N	2026-02-12 19:40:01	2026-02-12 19:40:01	t	61435590-df0a-4857-8432-b5eb94fe40a7	admincabang2
8	kasir2	kasir2@gmail.com	\N	$2y$12$uzYge4GaVNiP8rFOjTUWu.nd5wcd7oRClpTn7GCiAJEZ0l4HdQ8iG	\N	2026-02-12 19:41:16	2026-02-12 19:41:16	t	61435590-df0a-4857-8432-b5eb94fe40a7	kasir2
\.


--
-- Data for Name: vouchers; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."vouchers" ("id", "branch_id", "code", "type", "value", "start_at", "end_at", "min_total", "usage_limit", "active", "created_at", "updated_at") FROM stdin;
\.


--
-- Data for Name: wash_note_items; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."wash_note_items" ("id", "wash_note_id", "order_id", "qty", "process_status", "started_at", "finished_at", "note", "created_at", "updated_at") FROM stdin;
\.


--
-- Data for Name: wash_notes; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."wash_notes" ("id", "user_id", "branch_id", "note_date", "orders_count", "total_qty", "created_at", "updated_at") FROM stdin;
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

SELECT pg_catalog.setval('"public"."migrations_id_seq"', 36, true);


--
-- Name: permissions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('"public"."permissions_id_seq"', 1, false);


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('"public"."personal_access_tokens_id_seq"', 55, true);


--
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('"public"."roles_id_seq"', 5, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('"public"."users_id_seq"', 8, true);


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
-- Name: loyalty_accounts loyalty_accounts_customer_id_branch_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."loyalty_accounts"
    ADD CONSTRAINT "loyalty_accounts_customer_id_branch_id_unique" UNIQUE ("customer_id", "branch_id");


--
-- Name: loyalty_accounts loyalty_accounts_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."loyalty_accounts"
    ADD CONSTRAINT "loyalty_accounts_pkey" PRIMARY KEY ("id");


--
-- Name: loyalty_logs loyalty_logs_order_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."loyalty_logs"
    ADD CONSTRAINT "loyalty_logs_order_id_unique" UNIQUE ("order_id");


--
-- Name: loyalty_logs loyalty_logs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."loyalty_logs"
    ADD CONSTRAINT "loyalty_logs_pkey" PRIMARY KEY ("id");


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
-- Name: users users_username_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."users"
    ADD CONSTRAINT "users_username_unique" UNIQUE ("username");


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
-- Name: wash_note_items wash_note_items_order_id_unique_global; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."wash_note_items"
    ADD CONSTRAINT "wash_note_items_order_id_unique_global" UNIQUE ("order_id");


--
-- Name: wash_note_items wash_note_items_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."wash_note_items"
    ADD CONSTRAINT "wash_note_items_pkey" PRIMARY KEY ("id");


--
-- Name: wash_note_items wash_note_items_wash_note_id_order_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."wash_note_items"
    ADD CONSTRAINT "wash_note_items_wash_note_id_order_id_unique" UNIQUE ("wash_note_id", "order_id");


--
-- Name: wash_notes wash_notes_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."wash_notes"
    ADD CONSTRAINT "wash_notes_pkey" PRIMARY KEY ("id");


--
-- Name: wash_notes wash_notes_user_id_note_date_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."wash_notes"
    ADD CONSTRAINT "wash_notes_user_id_note_date_unique" UNIQUE ("user_id", "note_date");


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
-- Name: loyalty_accounts_branch_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "loyalty_accounts_branch_id_index" ON "public"."loyalty_accounts" USING "btree" ("branch_id");


--
-- Name: loyalty_accounts_customer_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "loyalty_accounts_customer_id_index" ON "public"."loyalty_accounts" USING "btree" ("customer_id");


--
-- Name: loyalty_logs_branch_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "loyalty_logs_branch_id_index" ON "public"."loyalty_logs" USING "btree" ("branch_id");


--
-- Name: loyalty_logs_customer_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "loyalty_logs_customer_id_index" ON "public"."loyalty_logs" USING "btree" ("customer_id");


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
-- Name: orders_ready_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "orders_ready_at_index" ON "public"."orders" USING "btree" ("ready_at");


--
-- Name: orders_received_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "orders_received_at_index" ON "public"."orders" USING "btree" ("received_at");


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
-- Name: wash_note_items_order_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "wash_note_items_order_id_index" ON "public"."wash_note_items" USING "btree" ("order_id");


--
-- Name: wash_notes_note_date_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "wash_notes_note_date_index" ON "public"."wash_notes" USING "btree" ("note_date");


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
-- Name: wash_note_items wash_note_items_order_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."wash_note_items"
    ADD CONSTRAINT "wash_note_items_order_id_foreign" FOREIGN KEY ("order_id") REFERENCES "public"."orders"("id") ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: wash_note_items wash_note_items_wash_note_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."wash_note_items"
    ADD CONSTRAINT "wash_note_items_wash_note_id_foreign" FOREIGN KEY ("wash_note_id") REFERENCES "public"."wash_notes"("id") ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: wash_notes wash_notes_branch_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."wash_notes"
    ADD CONSTRAINT "wash_notes_branch_id_foreign" FOREIGN KEY ("branch_id") REFERENCES "public"."branches"("id") ON UPDATE CASCADE ON DELETE SET NULL;


--
-- Name: wash_notes wash_notes_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "public"."wash_notes"
    ADD CONSTRAINT "wash_notes_user_id_foreign" FOREIGN KEY ("user_id") REFERENCES "public"."users"("id") ON UPDATE CASCADE ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

\unrestrict RR0bwcPd4aZJtm3d9iLKS1g8sCZQFxq4LxFpa5FuAPCQAn9O6PV6nHGPsKE0s2V

