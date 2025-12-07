--
-- PostgreSQL database dump
--

\restrict MOxdsBzcoQPDsg8Z8nUryA0qO4xcb1FjqOCoQVTRYoJj9dzi3KI6S2FeibmiK3u

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
71adee1b-91d7-43cc-a712-9eaac873c6a5	CBG-001	Cabang Utama	Alamat Cabang Utama	SLV	never	2025-11-25 14:29:33	2025-12-05 02:08:37
cd7ab9e8-4091-4bc5-b78a-98607725300a	CBG-02	Ujung Berung	Jl Ah Nasution	SLV	never	2025-12-06 19:18:07	2025-12-06 19:18:07
\.


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."cache" ("key", "value", "expiration") FROM stdin;
salve-cache-spatie.permission.cache	a:3:{s:5:"alias";a:0:{}s:11:"permissions";a:0:{}s:5:"roles";a:0:{}}	1765212481
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
1ec7273c-d85e-4d92-a10b-545820df93b8	71adee1b-91d7-43cc-a712-9eaac873c6a5	galuh	085865809424	Permata	\N	2025-11-25 14:36:35+07	2025-11-25 14:36:35+07
d699897e-14c0-417c-b745-5904d8e554ec	71adee1b-91d7-43cc-a712-9eaac873c6a5	Customer	081214695222	Permata Biru Block Ar.06	\N	2025-11-25 14:34:55+07	2025-12-04 19:15:18+07
\.


--
-- Data for Name: deliveries; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."deliveries" ("id", "order_id", "type", "zone_id", "fee", "assigned_to", "auto_assigned", "status", "handover_photo", "created_at", "updated_at") FROM stdin;
019aba2d-b26d-705c-ae5b-62dc30e0ebde	019aba2d-6a28-72ef-b18c-8c7a1f7d318e	delivery	\N	0.00	5	t	ON_THE_WAY	\N	2025-11-25 15:42:28	2025-12-05 02:51:32
019aba00-13be-72ac-9670-48efb8385682	019ab9ff-23cc-7064-978b-e932acbffe33	delivery	\N	0.00	5	t	ON_THE_WAY	\N	2025-11-25 14:52:38	2025-12-05 02:51:34
019af394-f45b-70f8-8999-11f242d3dfb9	019af38f-9084-70b8-be86-664f056e9e6d	delivery	\N	0.00	5	t	ASSIGNED	\N	2025-12-06 19:13:36	2025-12-06 19:13:36
\.


--
-- Data for Name: delivery_events; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."delivery_events" ("id", "delivery_id", "status", "note", "created_at", "updated_at") FROM stdin;
019aba00-13c1-7164-a863-8a362a0ca23c	019aba00-13be-72ac-9670-48efb8385682	CREATED	Delivery created	2025-11-25 14:52:38	2025-11-25 14:52:38
019aba00-13da-7273-a32c-66697e5beaf8	019aba00-13be-72ac-9670-48efb8385682	ASSIGNED	Auto-assigned courier #5	2025-11-25 14:52:38	2025-11-25 14:52:38
019aba2d-b272-7083-9cdd-5abfd5f3b2ff	019aba2d-b26d-705c-ae5b-62dc30e0ebde	CREATED	Delivery created	2025-11-25 15:42:28	2025-11-25 15:42:28
019aba2d-b282-7311-8289-85e728c2f934	019aba2d-b26d-705c-ae5b-62dc30e0ebde	ASSIGNED	Auto-assigned courier #5	2025-11-25 15:42:28	2025-11-25 15:42:28
019aeaeb-7aaf-700e-9df2-f35cfefa2a7e	019aba2d-b26d-705c-ae5b-62dc30e0ebde	ON_THE_WAY	\N	2025-12-05 02:51:32	2025-12-05 02:51:32
019aeaeb-833d-7189-8bb4-bf477bcbbeb7	019aba00-13be-72ac-9670-48efb8385682	ON_THE_WAY	\N	2025-12-05 02:51:34	2025-12-05 02:51:34
019af394-f463-722c-b8c2-105c90dbb5b0	019af394-f45b-70f8-8999-11f242d3dfb9	CREATED	Delivery created	2025-12-06 19:13:36	2025-12-06 19:13:36
019af394-f479-7060-9777-8a9daaa9ca8a	019af394-f45b-70f8-8999-11f242d3dfb9	ASSIGNED	Auto-assigned courier #5	2025-12-06 19:13:36	2025-12-06 19:13:36
\.


--
-- Data for Name: expenses; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."expenses" ("id", "branch_id", "category", "amount", "note", "proof_path", "created_at", "updated_at") FROM stdin;
019af39a-32fd-7142-96af-7a674cb5f70e	cd7ab9e8-4091-4bc5-b78a-98607725300a	Listrik	20000.00	\N	storage/uploads/expenses/W7xA8gA1orqkQBSIryRiEwV8broGzXeFLfvWPgCY.png	2025-12-06 19:19:20	2025-12-06 19:19:20
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
019ab9f5-1df2-719c-ab37-1f6b4ef59514	71adee1b-91d7-43cc-a712-9eaac873c6a5	SLV	7536	never	\N	2025-11-25 14:40:40	2025-12-06 19:07:43
019af399-d390-7398-a7c5-bb8a3e4c334e	cd7ab9e8-4091-4bc5-b78a-98607725300a	SLV	0	never	\N	2025-12-06 19:18:56	2025-12-06 19:18:56
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
019aed50-ff95-703e-9f77-527f2b1d249f	1ec7273c-d85e-4d92-a10b-545820df93b8	71adee1b-91d7-43cc-a712-9eaac873c6a5	6	6	2025-12-05 14:01:39	2025-12-05 17:06:23
019aed52-0df6-73a7-b9f1-933f8f3c3bbb	d699897e-14c0-417c-b745-5904d8e554ec	71adee1b-91d7-43cc-a712-9eaac873c6a5	4	4	2025-12-05 14:02:49	2025-12-06 19:07:43
\.


--
-- Data for Name: loyalty_logs; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."loyalty_logs" ("id", "order_id", "customer_id", "branch_id", "action", "before", "after", "created_at", "updated_at") FROM stdin;
019aed58-5cba-718d-bd4a-5cef9662446f	019aed52-0dec-72c7-b0fd-30eeb63efc55	d699897e-14c0-417c-b745-5904d8e554ec	71adee1b-91d7-43cc-a712-9eaac873c6a5	EARN	0	1	2025-12-05 14:09:42	2025-12-05 14:09:42
019aed5c-18c9-70fe-b98e-cf7f53767cb6	019aed5c-18b6-722c-bfb4-6c61f5c50085	1ec7273c-d85e-4d92-a10b-545820df93b8	71adee1b-91d7-43cc-a712-9eaac873c6a5	EARN	0	1	2025-12-05 14:13:47	2025-12-05 14:13:47
019aed94-0c5f-71c1-b822-f19e6de6b09a	019aed94-0c47-7188-acfd-7ddd7446abc1	1ec7273c-d85e-4d92-a10b-545820df93b8	71adee1b-91d7-43cc-a712-9eaac873c6a5	EARN	1	2	2025-12-05 15:14:54	2025-12-05 15:14:54
019aed9c-a6e8-735c-9011-790a72f90cc3	019aed9c-a6d3-7198-adc0-dc84bb42bd39	1ec7273c-d85e-4d92-a10b-545820df93b8	71adee1b-91d7-43cc-a712-9eaac873c6a5	EARN	2	3	2025-12-05 15:24:17	2025-12-05 15:24:17
019aeda7-3d32-739f-a866-cf62bfb66c79	019aeda7-3d1f-72c2-a267-17649f860a2d	1ec7273c-d85e-4d92-a10b-545820df93b8	71adee1b-91d7-43cc-a712-9eaac873c6a5	EARN	3	4	2025-12-05 15:35:51	2025-12-05 15:35:51
019aeddb-aa3e-714f-a653-a53daed96875	\N	1ec7273c-d85e-4d92-a10b-545820df93b8	71adee1b-91d7-43cc-a712-9eaac873c6a5	REWARD25	4	4	2025-12-05 16:33:07	2025-12-05 16:33:07
019aeddb-aa40-73a0-81b8-bbd360a12ab6	019aeddb-aa2d-714f-8242-4f97f324c8c5	1ec7273c-d85e-4d92-a10b-545820df93b8	71adee1b-91d7-43cc-a712-9eaac873c6a5	EARN	4	5	2025-12-05 16:33:07	2025-12-05 16:33:07
019aedfa-1f03-7342-bf12-c712580e86c6	019aedfa-1eed-7024-8eda-dbfe87958274	1ec7273c-d85e-4d92-a10b-545820df93b8	71adee1b-91d7-43cc-a712-9eaac873c6a5	EARN	5	6	2025-12-05 17:06:23	2025-12-05 17:06:23
019aee30-5f16-7036-87a1-2e3fa5852daf	019aee30-5f04-7344-afd2-e67042f17e25	d699897e-14c0-417c-b745-5904d8e554ec	71adee1b-91d7-43cc-a712-9eaac873c6a5	EARN	1	2	2025-12-05 18:05:38	2025-12-05 18:05:38
019aee47-84e7-729d-8ffe-201c16e01373	019aee47-84cc-711f-a089-15a5026b03c5	d699897e-14c0-417c-b745-5904d8e554ec	71adee1b-91d7-43cc-a712-9eaac873c6a5	EARN	2	3	2025-12-05 18:30:55	2025-12-05 18:30:55
019af38f-90a1-736e-b864-f25d316c2ad6	019af38f-9084-70b8-be86-664f056e9e6d	d699897e-14c0-417c-b745-5904d8e554ec	71adee1b-91d7-43cc-a712-9eaac873c6a5	EARN	3	4	2025-12-06 19:07:43	2025-12-06 19:07:43
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
30	2025_12_04_224859_add_dates_to_orders_table	3
31	2025_12_05_004843_add_username_to_users_table	4
32	2025_12_05_134121_create_loyalty_accounts_table	5
33	2025_12_05_134150_create_loyalty_logs_table	5
34	2025_12_05_134214_alter_orders_add_loyalty_columns	5
35	2025_12_07_160309_create_wash_notes_tables	6
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
4	App\\Models\\User	4
5	App\\Models\\User	5
2	App\\Models\\User	2
3	App\\Models\\User	3
3	App\\Models\\User	6
\.


--
-- Data for Name: order_items; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."order_items" ("id", "order_id", "service_id", "qty", "price", "total", "note", "created_at", "updated_at") FROM stdin;
019ab9ff-23d5-7257-8d9d-458178fc644d	019ab9ff-23cc-7064-978b-e932acbffe33	2f310fc1-a7ca-4b4e-88ff-391392b35215	1.00	125000.00	125000.00	\N	2025-11-25 14:51:37	2025-11-25 14:51:37
019aba2d-6a2d-7191-a102-2d1f28ca1257	019aba2d-6a28-72ef-b18c-8c7a1f7d318e	fde10d28-7dc9-4ffc-8630-b71e75b85345	1.00	50000.00	50000.00	\N	2025-11-25 15:42:09	2025-11-25 15:42:09
019ac9ab-c3b0-70fb-81fe-083b1763d9d3	019ac9ab-c3aa-7207-8ad0-0524b80f2762	fde10d28-7dc9-4ffc-8630-b71e75b85345	1.00	50000.00	50000.00	\N	2025-11-28 15:54:28	2025-11-28 15:54:28
019ae4a1-eef1-7234-9e45-c3dd825679f6	019ae4a1-eee9-7278-9dad-9b8b62abfc04	fde10d28-7dc9-4ffc-8630-b71e75b85345	1.00	50000.00	50000.00	\N	2025-12-03 21:33:29	2025-12-03 21:33:29
019ae4ac-c8bd-7335-9e1d-55a1e5cf3f68	019ae4ac-c8b8-71d6-a56f-986362087721	2f310fc1-a7ca-4b4e-88ff-391392b35215	1.00	125000.00	125000.00	\N	2025-12-03 21:45:20	2025-12-03 21:45:20
019ae4af-2b55-7208-bcb8-631dd6fdc7ce	019ae4af-2b4f-73a8-b133-f860a306ea5a	2f310fc1-a7ca-4b4e-88ff-391392b35215	1.00	125000.00	125000.00	\N	2025-12-03 21:47:56	2025-12-03 21:47:56
019ae52d-b3d6-73e8-ac6b-c0f5d7f21d13	019ae52d-b3cd-7257-a377-b2c5eb8630c2	2f310fc1-a7ca-4b4e-88ff-391392b35215	1.00	125000.00	125000.00	\N	2025-12-04 00:06:08	2025-12-04 00:06:08
019ae54f-0567-7270-a896-2092ef57fdff	019ae54f-0561-71ee-afe9-ba6b3e36da21	2f310fc1-a7ca-4b4e-88ff-391392b35215	1.00	125000.00	125000.00	\N	2025-12-04 00:42:32	2025-12-04 00:42:32
019ae54f-ef60-72f4-97f4-b657d5ae7461	019ae54f-ef5b-7231-b71e-5818469d5122	2f310fc1-a7ca-4b4e-88ff-391392b35215	1.00	125000.00	125000.00	\N	2025-12-04 00:43:32	2025-12-04 00:43:32
019ae552-85af-71d5-8fbd-f3fb4f52f417	019ae552-85a9-71d2-b3bb-6c0e9dc72e7d	2f310fc1-a7ca-4b4e-88ff-391392b35215	1.00	125000.00	125000.00	\N	2025-12-04 00:46:21	2025-12-04 00:46:21
019ae556-532c-730d-86ac-44962070b19c	019ae556-5326-715f-80b7-ce7109cd3119	2f310fc1-a7ca-4b4e-88ff-391392b35215	1.00	125000.00	125000.00	\N	2025-12-04 00:50:31	2025-12-04 00:50:31
019ae559-4e33-70b7-b717-1b60269c65f1	019ae559-4e2c-7015-8de9-7edffd25cb86	fde10d28-7dc9-4ffc-8630-b71e75b85345	1.00	50000.00	50000.00	\N	2025-12-04 00:53:46	2025-12-04 00:53:46
019ae55b-2d30-731a-af95-3baa89a4da66	019ae55b-2d29-7336-bb94-19e2d2f46bdf	2f310fc1-a7ca-4b4e-88ff-391392b35215	1.00	125000.00	125000.00	\N	2025-12-04 00:55:49	2025-12-04 00:55:49
019ae560-403d-7339-a587-60c8cbdf13aa	019ae560-4035-7356-8340-77b46924dd8f	2f310fc1-a7ca-4b4e-88ff-391392b35215	1.00	125000.00	125000.00	\N	2025-12-04 01:01:21	2025-12-04 01:01:21
019ae560-590f-70c1-88b1-9cb952f406aa	019ae560-5908-72d5-abbc-d29028453a48	fde10d28-7dc9-4ffc-8630-b71e75b85345	1.00	50000.00	50000.00	\N	2025-12-04 01:01:28	2025-12-04 01:01:28
019ae583-58ba-7222-8b59-8445d68d9938	019ae583-58b3-7200-b75b-fc0fd5a1b57d	2f310fc1-a7ca-4b4e-88ff-391392b35215	1.00	125000.00	125000.00	\N	2025-12-04 01:39:41	2025-12-04 01:39:41
019ae5a4-5ca2-7176-a539-8376bd1481c1	019ae5a4-5c9b-7056-a395-2b715e3f3854	2f310fc1-a7ca-4b4e-88ff-391392b35215	1.00	125000.00	125000.00	\N	2025-12-04 02:15:45	2025-12-04 02:15:45
019ae91b-98fc-7004-878b-fb73bdadd3ab	019ae5b4-c8eb-71a1-9037-02655a4401f5	2f310fc1-a7ca-4b4e-88ff-391392b35215	2.00	125000.00	250000.00	\N	2025-12-04 18:24:51	2025-12-04 18:24:51
019ae91b-9901-730d-aa93-d95e07ed3346	019ae5b4-c8eb-71a1-9037-02655a4401f5	fde10d28-7dc9-4ffc-8630-b71e75b85345	1.00	50000.00	50000.00	\N	2025-12-04 18:24:51	2025-12-04 18:24:51
019ae91c-4e8c-7300-bfa5-711cb36c9786	019ae5a3-ccbf-7226-8cd4-2fa82c64b148	2f310fc1-a7ca-4b4e-88ff-391392b35215	2.00	125000.00	250000.00	\N	2025-12-04 18:25:37	2025-12-04 18:25:37
019ae91c-4e90-72c8-bfdf-5bc6f893177a	019ae5a3-ccbf-7226-8cd4-2fa82c64b148	fde10d28-7dc9-4ffc-8630-b71e75b85345	1.00	50000.00	50000.00	\N	2025-12-04 18:25:37	2025-12-04 18:25:37
019ae920-0e36-7204-a4e3-c23f54a53209	019ae5a5-3cc9-7004-b6db-797b465b84f8	2f310fc1-a7ca-4b4e-88ff-391392b35215	1.00	125000.00	125000.00	\N	2025-12-04 18:29:43	2025-12-04 18:29:43
019ae920-0e3b-72e5-b062-d1212fd979bd	019ae5a5-3cc9-7004-b6db-797b465b84f8	fde10d28-7dc9-4ffc-8630-b71e75b85345	2.00	50000.00	100000.00	\N	2025-12-04 18:29:43	2025-12-04 18:29:43
019ae94b-75b2-702f-b910-e2ae7039dbb7	019ae94b-75a8-724e-8bf5-1515a82d99d8	2f310fc1-a7ca-4b4e-88ff-391392b35215	1.00	125000.00	125000.00	\N	2025-12-04 19:17:08	2025-12-04 19:17:08
019ae94b-e3fd-7027-bce6-59c44857b9db	019ae94b-e3f8-738c-9f34-e08d91ee7699	2f310fc1-a7ca-4b4e-88ff-391392b35215	2.00	125000.00	250000.00	\N	2025-12-04 19:17:36	2025-12-04 19:17:36
019aea3a-5f96-738d-8b12-601863f10da5	019aea3a-5f8a-70a1-9c9d-054f570702af	2f310fc1-a7ca-4b4e-88ff-391392b35215	1.00	125000.00	125000.00	\N	2025-12-04 23:38:05	2025-12-04 23:38:05
019aea5a-f64c-7218-958a-059df091e480	019aea5a-f645-7183-a2e1-3959e5057158	2f310fc1-a7ca-4b4e-88ff-391392b35215	1.00	125000.00	125000.00	\N	2025-12-05 00:13:41	2025-12-05 00:13:41
019aed50-ff8d-72b6-9add-19a8be36b6dc	019aed50-ff83-7018-becb-e2dd61cfc781	2f310fc1-a7ca-4b4e-88ff-391392b35215	1.00	125000.00	125000.00	\N	2025-12-05 14:01:39	2025-12-05 14:01:39
019aed52-0df2-7202-ac8e-36d6e85779f1	019aed52-0dec-72c7-b0fd-30eeb63efc55	fde10d28-7dc9-4ffc-8630-b71e75b85345	2.00	50000.00	100000.00	\N	2025-12-05 14:02:49	2025-12-05 14:02:49
019aed5c-18be-73ed-bb09-1bc3b7426b4a	019aed5c-18b6-722c-bfb4-6c61f5c50085	2f310fc1-a7ca-4b4e-88ff-391392b35215	1.00	125000.00	125000.00	\N	2025-12-05 14:13:47	2025-12-05 14:13:47
019aed94-0c50-7355-86e0-ae663a3999f7	019aed94-0c47-7188-acfd-7ddd7446abc1	2f310fc1-a7ca-4b4e-88ff-391392b35215	1.00	125000.00	125000.00	\N	2025-12-05 15:14:54	2025-12-05 15:14:54
019aed9c-a6da-7045-8769-41155491d4fe	019aed9c-a6d3-7198-adc0-dc84bb42bd39	2f310fc1-a7ca-4b4e-88ff-391392b35215	1.00	125000.00	125000.00	\N	2025-12-05 15:24:17	2025-12-05 15:24:17
019aeda7-3d25-7071-87d5-d4ce5110c150	019aeda7-3d1f-72c2-a267-17649f860a2d	2f310fc1-a7ca-4b4e-88ff-391392b35215	1.00	125000.00	125000.00	\N	2025-12-05 15:35:51	2025-12-05 15:35:51
019aeddb-aa33-7389-901f-a221a8af53e8	019aeddb-aa2d-714f-8242-4f97f324c8c5	2f310fc1-a7ca-4b4e-88ff-391392b35215	1.00	125000.00	125000.00	\N	2025-12-05 16:33:07	2025-12-05 16:33:07
019aeddb-aa37-7378-b949-c98a77c0aa31	019aeddb-aa2d-714f-8242-4f97f324c8c5	fde10d28-7dc9-4ffc-8630-b71e75b85345	1.00	50000.00	50000.00	\N	2025-12-05 16:33:07	2025-12-05 16:33:07
019aedfa-1ef4-71d9-a6ff-5c2bc3a70275	019aedfa-1eed-7024-8eda-dbfe87958274	2f310fc1-a7ca-4b4e-88ff-391392b35215	1.00	125000.00	125000.00	\N	2025-12-05 17:06:23	2025-12-05 17:06:23
019aee30-5f0a-70b5-b37a-a1609626da2b	019aee30-5f04-7344-afd2-e67042f17e25	2f310fc1-a7ca-4b4e-88ff-391392b35215	1.00	125000.00	125000.00	\N	2025-12-05 18:05:38	2025-12-05 18:05:38
019af38f-908c-7179-957f-3e03d90e318f	019af38f-9084-70b8-be86-664f056e9e6d	2f310fc1-a7ca-4b4e-88ff-391392b35215	1.00	125000.00	125000.00	\N	2025-12-06 19:07:43	2025-12-06 19:07:43
019af38f-9093-706f-a75f-1290d7b35180	019af38f-9084-70b8-be86-664f056e9e6d	fde10d28-7dc9-4ffc-8630-b71e75b85345	1.00	50000.00	50000.00	\N	2025-12-06 19:07:43	2025-12-06 19:07:43
019af3a4-433d-71ef-adff-66596708b141	019aee47-84cc-711f-a089-15a5026b03c5	2f310fc1-a7ca-4b4e-88ff-391392b35215	1.00	125000.00	125000.00	\N	2025-12-06 19:30:19	2025-12-06 19:30:19
019af3a4-4341-72eb-9cad-d12cbf46779d	019aee47-84cc-711f-a089-15a5026b03c5	fde10d28-7dc9-4ffc-8630-b71e75b85345	1.00	50000.00	50000.00	\N	2025-12-06 19:30:19	2025-12-06 19:30:19
019af3a4-4344-7318-82cd-bc1092243905	019aee47-84cc-711f-a089-15a5026b03c5	86ef04eb-777b-4fd3-b559-181577178e32	1.00	500000.00	500000.00	\N	2025-12-06 19:30:19	2025-12-06 19:30:19
\.


--
-- Data for Name: order_photos; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."order_photos" ("id", "order_id", "kind", "path", "created_at", "updated_at") FROM stdin;
019ae90c-b60e-737f-a385-0cf68a9d5234	019ae5b4-c8eb-71a1-9037-02655a4401f5	before	storage/uploads/orders/019ae5b4-c8eb-71a1-9037-02655a4401f5/before/HDe2EEfThPqOgb51jEXalTH7snHNoFZqitxDAGQh.png	2025-12-04 18:08:35	2025-12-04 18:08:35
019af38f-9147-73c6-9888-ac676f369cf2	019af38f-9084-70b8-be86-664f056e9e6d	before	storage/uploads/orders/019af38f-9084-70b8-be86-664f056e9e6d/before/JtSeX3Ti4HgI36BesA75sJTOQwYXt6mMuN7687lN.png	2025-12-06 19:07:43	2025-12-06 19:07:43
\.


--
-- Data for Name: order_vouchers; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."order_vouchers" ("id", "order_id", "voucher_id", "applied_amount", "applied_by", "applied_at", "created_at", "updated_at") FROM stdin;
019ac9ab-c558-739c-abe7-10b20157f530	019ac9ab-c3aa-7207-8ad0-0524b80f2762	019ac9ab-60d7-70e3-a1bd-7df8098dc814	20000.00	2	2025-11-28 15:54:28+07	2025-11-28 15:54:28+07	2025-11-28 15:54:28+07
\.


--
-- Data for Name: orders; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."orders" ("id", "branch_id", "customer_id", "number", "status", "subtotal", "discount", "grand_total", "paid_amount", "due_amount", "notes", "created_at", "updated_at", "payment_status", "dp_amount", "paid_at", "invoice_no", "created_by", "received_at", "ready_at", "loyalty_reward", "loyalty_discount") FROM stdin;
019ae552-85a9-71d2-b3bb-6c0e9dc72e7d	71adee1b-91d7-43cc-a712-9eaac873c6a5	1ec7273c-d85e-4d92-a10b-545820df93b8	SLV-202512-007511	QUEUE	125000.00	0.00	125000.00	125000.00	0.00	\N	2025-12-04 00:46:21	2025-12-04 00:46:47	PAID	0.00	2025-12-03 10:46:00	INV-04-12-7511	3	\N	\N	NONE	0.00
019ab9ff-23cc-7064-978b-e932acbffe33	71adee1b-91d7-43cc-a712-9eaac873c6a5	1ec7273c-d85e-4d92-a10b-545820df93b8	SLV-202511-007502	DELIVERING	125000.00	0.00	125000.00	125000.00	0.00	\N	2025-11-25 14:51:37	2025-11-25 14:57:34	PAID	0.00	2025-11-25 07:57:00	INV-25-11-7502	3	\N	\N	NONE	0.00
019aba2d-6a28-72ef-b18c-8c7a1f7d318e	71adee1b-91d7-43cc-a712-9eaac873c6a5	d699897e-14c0-417c-b745-5904d8e554ec	SLV-202511-007503	DELIVERING	50000.00	0.00	50000.00	50000.00	0.00	\N	2025-11-25 15:42:09	2025-11-25 15:42:28	PAID	0.00	2025-11-25 08:42:12	INV-25-11-7503	3	\N	\N	NONE	0.00
019ac9ab-c3aa-7207-8ad0-0524b80f2762	71adee1b-91d7-43cc-a712-9eaac873c6a5	1ec7273c-d85e-4d92-a10b-545820df93b8	SLV-202511-007504	QUEUE	50000.00	20000.00	30000.00	30000.00	0.00	\N	2025-11-28 15:54:28	2025-11-28 15:57:33	PAID	0.00	2025-11-28 01:57:00	INV-28-11-7504	2	\N	\N	NONE	0.00
019aea5a-f645-7183-a2e1-3959e5057158	71adee1b-91d7-43cc-a712-9eaac873c6a5	d699897e-14c0-417c-b745-5904d8e554ec	SLV-202512-007525	QUEUE	125000.00	0.00	125000.00	0.00	125000.00	\N	2025-12-05 00:13:41	2025-12-05 00:13:41	PENDING	0.00	\N	INV-05-12-7525	3	2025-12-04 17:13:00	2025-12-08 05:50:00	NONE	0.00
019ae4af-2b4f-73a8-b133-f860a306ea5a	71adee1b-91d7-43cc-a712-9eaac873c6a5	1ec7273c-d85e-4d92-a10b-545820df93b8	SLV-202512-007507	QUEUE	125000.00	0.00	125000.00	125000.00	0.00	\N	2025-12-03 21:47:56	2025-12-03 23:46:48	PAID	0.00	2025-12-03 09:46:00	INV-03-12-7507	3	\N	\N	NONE	0.00
019ae556-5326-715f-80b7-ce7109cd3119	71adee1b-91d7-43cc-a712-9eaac873c6a5	1ec7273c-d85e-4d92-a10b-545820df93b8	SLV-202512-007512	QUEUE	125000.00	0.00	125000.00	125000.00	0.00	\N	2025-12-04 00:50:31	2025-12-04 00:55:55	PAID	0.00	2025-12-03 10:55:00	INV-04-12-7512	3	\N	\N	NONE	0.00
019ae52d-b3cd-7257-a377-b2c5eb8630c2	71adee1b-91d7-43cc-a712-9eaac873c6a5	1ec7273c-d85e-4d92-a10b-545820df93b8	SLV-202512-007508	QUEUE	125000.00	0.00	125000.00	125000.00	0.00	\N	2025-12-04 00:06:08	2025-12-04 00:06:09	PAID	0.00	2025-12-03 17:06:12	INV-04-12-7508	3	\N	\N	NONE	0.00
019ae4ac-c8b8-71d6-a56f-986362087721	71adee1b-91d7-43cc-a712-9eaac873c6a5	1ec7273c-d85e-4d92-a10b-545820df93b8	SLV-202512-007506	QUEUE	125000.00	0.00	125000.00	125000.00	0.00	\N	2025-12-03 21:45:20	2025-12-04 00:34:04	PAID	0.00	2025-12-03 10:34:00	INV-03-12-7506	3	\N	\N	NONE	0.00
019ae4a1-eee9-7278-9dad-9b8b62abfc04	71adee1b-91d7-43cc-a712-9eaac873c6a5	1ec7273c-d85e-4d92-a10b-545820df93b8	SLV-202512-007505	QUEUE	50000.00	0.00	50000.00	50000.00	0.00	\N	2025-12-03 21:33:29	2025-12-04 00:42:10	PAID	0.00	2025-12-03 10:42:00	INV-03-12-7505	3	\N	\N	NONE	0.00
019ae54f-0561-71ee-afe9-ba6b3e36da21	71adee1b-91d7-43cc-a712-9eaac873c6a5	1ec7273c-d85e-4d92-a10b-545820df93b8	SLV-202512-007509	QUEUE	125000.00	0.00	125000.00	125000.00	0.00	\N	2025-12-04 00:42:32	2025-12-04 00:42:38	PAID	0.00	2025-12-03 10:42:00	INV-04-12-7509	3	\N	\N	NONE	0.00
019ae559-4e2c-7015-8de9-7edffd25cb86	71adee1b-91d7-43cc-a712-9eaac873c6a5	1ec7273c-d85e-4d92-a10b-545820df93b8	SLV-202512-007513	QUEUE	50000.00	0.00	50000.00	50000.00	0.00	\N	2025-12-04 00:53:46	2025-12-04 00:59:58	PAID	0.00	2025-12-03 10:59:00	INV-04-12-7513	3	\N	\N	NONE	0.00
019ae54f-ef5b-7231-b71e-5818469d5122	71adee1b-91d7-43cc-a712-9eaac873c6a5	1ec7273c-d85e-4d92-a10b-545820df93b8	SLV-202512-007510	QUEUE	125000.00	0.00	125000.00	125000.00	0.00	\N	2025-12-04 00:43:32	2025-12-04 00:45:36	PAID	0.00	2025-12-03 10:45:00	INV-04-12-7510	3	\N	\N	NONE	0.00
019ae55b-2d29-7336-bb94-19e2d2f46bdf	71adee1b-91d7-43cc-a712-9eaac873c6a5	1ec7273c-d85e-4d92-a10b-545820df93b8	SLV-202512-007514	WASHING	125000.00	0.00	125000.00	125000.00	0.00	\N	2025-12-04 00:55:49	2025-12-04 01:01:01	PAID	0.00	2025-12-03 11:00:00	INV-04-12-7514	3	\N	\N	NONE	0.00
019ae5b4-c8eb-71a1-9037-02655a4401f5	71adee1b-91d7-43cc-a712-9eaac873c6a5	1ec7273c-d85e-4d92-a10b-545820df93b8	SLV-202512-007521	DRYING	300000.00	0.00	300000.00	0.00	300000.00	\N	2025-12-04 02:33:41	2025-12-04 18:24:51	PENDING	0.00	\N	INV-04-12-7521	3	\N	\N	NONE	0.00
019ae560-4035-7356-8340-77b46924dd8f	71adee1b-91d7-43cc-a712-9eaac873c6a5	1ec7273c-d85e-4d92-a10b-545820df93b8	SLV-202512-007515	QUEUE	125000.00	0.00	125000.00	125000.00	0.00	\N	2025-12-04 01:01:21	2025-12-04 01:02:26	PAID	0.00	2025-12-03 11:02:00	INV-04-12-7515	3	\N	\N	NONE	0.00
019ae560-5908-72d5-abbc-d29028453a48	71adee1b-91d7-43cc-a712-9eaac873c6a5	1ec7273c-d85e-4d92-a10b-545820df93b8	SLV-202512-007516	QUEUE	50000.00	0.00	50000.00	50000.00	0.00	\N	2025-12-04 01:01:28	2025-12-04 01:33:46	PAID	0.00	2025-12-03 11:33:00	INV-04-12-7516	3	\N	\N	NONE	0.00
019ae583-58b3-7200-b75b-fc0fd5a1b57d	71adee1b-91d7-43cc-a712-9eaac873c6a5	1ec7273c-d85e-4d92-a10b-545820df93b8	SLV-202512-007517	QUEUE	125000.00	0.00	125000.00	0.00	125000.00	\N	2025-12-04 01:39:41	2025-12-04 01:39:41	PENDING	0.00	\N	INV-04-12-7517	3	\N	\N	NONE	0.00
019aed50-ff83-7018-becb-e2dd61cfc781	71adee1b-91d7-43cc-a712-9eaac873c6a5	1ec7273c-d85e-4d92-a10b-545820df93b8	SLV-202512-007526	QUEUE	125000.00	0.00	125000.00	0.00	125000.00	\N	2025-12-05 14:01:39	2025-12-05 14:01:39	PENDING	0.00	\N	INV-05-12-7526	3	2025-12-05 07:01:20	2025-12-09 09:00:00	NONE	0.00
019ae5a3-ccbf-7226-8cd4-2fa82c64b148	71adee1b-91d7-43cc-a712-9eaac873c6a5	1ec7273c-d85e-4d92-a10b-545820df93b8	SLV-202512-007518	QUEUE	300000.00	0.00	300000.00	300000.00	0.00	\N	2025-12-04 02:15:08	2025-12-04 18:25:55	PAID	0.00	2025-12-04 04:25:00	INV-04-12-7518	3	\N	\N	NONE	0.00
019ae5a4-5c9b-7056-a395-2b715e3f3854	71adee1b-91d7-43cc-a712-9eaac873c6a5	1ec7273c-d85e-4d92-a10b-545820df93b8	SLV-202512-007519	QUEUE	125000.00	0.00	125000.00	125000.00	0.00	\N	2025-12-04 02:15:45	2025-12-04 02:18:17	PAID	20000.00	2025-12-03 12:18:00	INV-04-12-7519	3	\N	\N	NONE	0.00
019ae5a5-3cc9-7004-b6db-797b465b84f8	71adee1b-91d7-43cc-a712-9eaac873c6a5	1ec7273c-d85e-4d92-a10b-545820df93b8	SLV-202512-007520	QUEUE	225000.00	0.00	225000.00	125000.00	100000.00	\N	2025-12-04 02:16:42	2025-12-04 18:29:43	PAID	0.00	2025-12-03 19:16:42	INV-04-12-7520	3	\N	\N	NONE	0.00
019ae94b-75a8-724e-8bf5-1515a82d99d8	71adee1b-91d7-43cc-a712-9eaac873c6a5	d699897e-14c0-417c-b745-5904d8e554ec	SLV-202512-007522	QUEUE	125000.00	0.00	125000.00	25000.00	100000.00	\N	2025-12-04 19:17:08	2025-12-04 19:17:08	DP	25000.00	\N	INV-04-12-7522	3	\N	\N	NONE	0.00
019ae94b-e3f8-738c-9f34-e08d91ee7699	71adee1b-91d7-43cc-a712-9eaac873c6a5	d699897e-14c0-417c-b745-5904d8e554ec	SLV-202512-007523	QUEUE	250000.00	0.00	250000.00	50000.00	200000.00	\N	2025-12-04 19:17:36	2025-12-04 19:17:36	DP	50000.00	\N	INV-04-12-7523	3	\N	\N	NONE	0.00
019aea3a-5f8a-70a1-9c9d-054f570702af	71adee1b-91d7-43cc-a712-9eaac873c6a5	1ec7273c-d85e-4d92-a10b-545820df93b8	SLV-202512-007524	QUEUE	125000.00	0.00	125000.00	0.00	125000.00	\N	2025-12-04 23:38:05	2025-12-04 23:38:05	PENDING	0.00	\N	INV-04-12-7524	2	2025-12-04 16:37:52	\N	NONE	0.00
019aed52-0dec-72c7-b0fd-30eeb63efc55	71adee1b-91d7-43cc-a712-9eaac873c6a5	d699897e-14c0-417c-b745-5904d8e554ec	SLV-202512-007527	PICKED_UP	100000.00	0.00	100000.00	100000.00	0.00	\N	2025-12-05 14:02:49	2025-12-05 14:09:42	PAID	0.00	2025-12-05 00:03:00	INV-05-12-7527	3	2025-12-05 07:02:08	2025-12-09 05:00:00	NONE	0.00
019aed5c-18b6-722c-bfb4-6c61f5c50085	71adee1b-91d7-43cc-a712-9eaac873c6a5	1ec7273c-d85e-4d92-a10b-545820df93b8	SLV-202512-007528	QUEUE	125000.00	0.00	125000.00	0.00	125000.00	\N	2025-12-05 14:13:47	2025-12-05 14:13:47	PENDING	0.00	\N	INV-05-12-7528	3	2025-12-05 07:13:28	2025-12-09 05:00:00	NONE	0.00
019aedfa-1eed-7024-8eda-dbfe87958274	71adee1b-91d7-43cc-a712-9eaac873c6a5	1ec7273c-d85e-4d92-a10b-545820df93b8	SLV-202512-007533	QUEUE	125000.00	0.00	125000.00	125000.00	0.00	\N	2025-12-05 17:06:23	2025-12-05 17:10:43	PAID	0.00	2025-12-05 03:10:00	INV-05-12-7533	3	2025-12-05 10:06:02	2025-12-09 05:03:00	NONE	0.00
019aed94-0c47-7188-acfd-7ddd7446abc1	71adee1b-91d7-43cc-a712-9eaac873c6a5	1ec7273c-d85e-4d92-a10b-545820df93b8	SLV-202512-007529	QUEUE	125000.00	0.00	125000.00	125000.00	0.00	\N	2025-12-05 15:14:54	2025-12-05 15:14:54	PAID	0.00	2025-12-05 08:14:54	INV-05-12-7529	3	2025-12-05 08:14:39	\N	NONE	0.00
019aed9c-a6d3-7198-adc0-dc84bb42bd39	71adee1b-91d7-43cc-a712-9eaac873c6a5	1ec7273c-d85e-4d92-a10b-545820df93b8	SLV-202512-007530	QUEUE	125000.00	0.00	125000.00	0.00	125000.00	\N	2025-12-05 15:24:17	2025-12-05 15:24:17	PENDING	0.00	\N	INV-05-12-7530	3	2025-12-05 08:23:31	2025-12-09 05:00:00	NONE	0.00
019aeda7-3d1f-72c2-a267-17649f860a2d	71adee1b-91d7-43cc-a712-9eaac873c6a5	1ec7273c-d85e-4d92-a10b-545820df93b8	SLV-202512-007531	QUEUE	125000.00	0.00	125000.00	0.00	125000.00	\N	2025-12-05 15:35:51	2025-12-05 15:35:51	PENDING	0.00	\N	INV-05-12-7531	3	2025-12-05 08:35:35	2025-12-09 05:03:00	NONE	0.00
019aeddb-aa2d-714f-8242-4f97f324c8c5	71adee1b-91d7-43cc-a712-9eaac873c6a5	1ec7273c-d85e-4d92-a10b-545820df93b8	SLV-202512-007532	QUEUE	175000.00	43750.00	131250.00	0.00	131250.00	\N	2025-12-05 16:33:07	2025-12-05 16:33:07	PENDING	0.00	\N	INV-05-12-7532	3	2025-12-05 09:33:01	\N	DISC25	43750.00
019aee30-5f04-7344-afd2-e67042f17e25	71adee1b-91d7-43cc-a712-9eaac873c6a5	d699897e-14c0-417c-b745-5904d8e554ec	SLV-202512-007534	QUEUE	125000.00	0.00	125000.00	0.00	125000.00	\N	2025-12-05 18:05:38	2025-12-05 18:05:38	PENDING	0.00	\N	INV-05-12-7534	3	2025-12-05 11:05:17	2025-12-09 06:30:00	NONE	0.00
019af38f-9084-70b8-be86-664f056e9e6d	71adee1b-91d7-43cc-a712-9eaac873c6a5	d699897e-14c0-417c-b745-5904d8e554ec	SLV-202512-007536	CANCELED	175000.00	0.00	175000.00	175000.00	0.00	\N	2025-12-06 19:07:43	2025-12-06 19:27:44	PAID	0.00	2025-12-06 05:09:00	INV-06-12-7536	3	2025-12-06 19:07:00	2025-12-09 12:30:00	NONE	0.00
019aee47-84cc-711f-a089-15a5026b03c5	71adee1b-91d7-43cc-a712-9eaac873c6a5	d699897e-14c0-417c-b745-5904d8e554ec	SLV-202512-007535	WASHING	675000.00	756250.00	0.00	0.00	0.00	\N	2025-12-05 18:30:55	2025-12-06 19:30:19	PENDING	0.00	\N	INV-05-12-7535	3	2025-12-04 21:30:00	2025-12-08 15:23:00	DISC25	168750.00
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
33dcc9b6-9d1b-4ea6-9375-303b2e13f8dd	019ac9ab-c3aa-7207-8ad0-0524b80f2762	QRIS	30000.00	2025-11-28 01:57:00+07	\N	2025-11-28 15:57:33+07	2025-11-28 15:57:33+07
bcc8ce85-d6de-4cb4-b9c1-8d807204c61c	019ae4af-2b4f-73a8-b133-f860a306ea5a	CASH	125000.00	2025-12-03 09:46:00+07	\N	2025-12-03 23:46:48+07	2025-12-03 23:46:48+07
76270ee7-4141-4a86-9e58-81658959e9fc	019ae52d-b3cd-7257-a377-b2c5eb8630c2	QRIS	125000.00	2025-12-03 17:06:12+07	\N	2025-12-04 00:06:09+07	2025-12-04 00:06:09+07
a710774f-bb6b-493b-abf3-c86e062b26d5	019ae4ac-c8b8-71d6-a56f-986362087721	CASH	125000.00	2025-12-03 10:34:00+07	\N	2025-12-04 00:34:04+07	2025-12-04 00:34:04+07
297545e1-c26e-4007-981b-3d08c00e36ee	019ae4a1-eee9-7278-9dad-9b8b62abfc04	CASH	50000.00	2025-12-03 10:42:00+07	\N	2025-12-04 00:42:10+07	2025-12-04 00:42:10+07
4b00fc2c-5649-4928-8f39-8e74e848bda2	019ae54f-0561-71ee-afe9-ba6b3e36da21	CASH	125000.00	2025-12-03 10:42:00+07	\N	2025-12-04 00:42:38+07	2025-12-04 00:42:38+07
d1726480-a99c-4f3a-b1b5-10e55b54932d	019ae54f-ef5b-7231-b71e-5818469d5122	CASH	125000.00	2025-12-03 10:45:00+07	\N	2025-12-04 00:45:36+07	2025-12-04 00:45:36+07
c4bda2ad-a616-40f4-9a40-7abe35464fae	019ae552-85a9-71d2-b3bb-6c0e9dc72e7d	QRIS	125000.00	2025-12-03 10:46:00+07	\N	2025-12-04 00:46:47+07	2025-12-04 00:46:47+07
81711d6e-ca13-4b7e-9e1b-f075916e5773	019ae556-5326-715f-80b7-ce7109cd3119	CASH	125000.00	2025-12-03 10:55:00+07	\N	2025-12-04 00:55:55+07	2025-12-04 00:55:55+07
f03f73ba-7f74-49b6-b196-1646ee799e30	019ae559-4e2c-7015-8de9-7edffd25cb86	CASH	50000.00	2025-12-03 10:59:00+07	\N	2025-12-04 00:59:58+07	2025-12-04 00:59:58+07
1d20a618-0e03-4e74-9f6d-1e3335ba0219	019ae55b-2d29-7336-bb94-19e2d2f46bdf	CASH	125000.00	2025-12-03 11:00:00+07	\N	2025-12-04 01:01:01+07	2025-12-04 01:01:01+07
0737d2dc-6356-41a5-b864-ed589130860a	019ae560-4035-7356-8340-77b46924dd8f	CASH	125000.00	2025-12-03 11:02:00+07	\N	2025-12-04 01:02:26+07	2025-12-04 01:02:26+07
6739ba2d-2ce1-48ea-b04a-aa981f9b5a05	019ae560-5908-72d5-abbc-d29028453a48	CASH	50000.00	2025-12-03 11:33:00+07	\N	2025-12-04 01:33:46+07	2025-12-04 01:33:46+07
1ec1267f-2476-4e0f-b852-59c6202ca607	019ae5a4-5c9b-7056-a395-2b715e3f3854	DP	20000.00	2025-12-03 19:15:45+07	\N	2025-12-04 02:15:45+07	2025-12-04 02:15:45+07
3423271c-c88f-452b-bc9c-49188180ad4f	019ae5a5-3cc9-7004-b6db-797b465b84f8	CASH	125000.00	2025-12-03 19:16:42+07	\N	2025-12-04 02:16:42+07	2025-12-04 02:16:42+07
17967d6b-505b-4b1f-983f-bd98b101504c	019ae5a4-5c9b-7056-a395-2b715e3f3854	CASH	105000.00	2025-12-03 12:18:00+07	\N	2025-12-04 02:18:17+07	2025-12-04 02:18:17+07
1f446fbc-987a-4b8b-8630-26aa14db01da	019ae5a3-ccbf-7226-8cd4-2fa82c64b148	CASH	300000.00	2025-12-04 04:25:00+07	\N	2025-12-04 18:25:55+07	2025-12-04 18:25:55+07
d73b7af7-f767-4862-83da-c51422a05650	019ae94b-75a8-724e-8bf5-1515a82d99d8	DP	25000.00	2025-12-04 12:17:07+07	\N	2025-12-04 19:17:08+07	2025-12-04 19:17:08+07
8591f201-bc7e-4785-b532-470a130b7da2	019ae94b-e3f8-738c-9f34-e08d91ee7699	DP	50000.00	2025-12-04 12:17:36+07	\N	2025-12-04 19:17:36+07	2025-12-04 19:17:36+07
32367823-25ce-4d5d-846e-60f29e816acf	019aed52-0dec-72c7-b0fd-30eeb63efc55	CASH	100000.00	2025-12-05 00:03:00+07	\N	2025-12-05 14:03:07+07	2025-12-05 14:03:07+07
6adc4985-2eb7-4659-b3d5-d64ad44d4071	019aed94-0c47-7188-acfd-7ddd7446abc1	QRIS	125000.00	2025-12-05 08:14:54+07	\N	2025-12-05 15:14:54+07	2025-12-05 15:14:54+07
cc1eea44-0671-4251-9059-256befab43e3	019aedfa-1eed-7024-8eda-dbfe87958274	CASH	125000.00	2025-12-05 03:10:00+07	\N	2025-12-05 17:10:43+07	2025-12-05 17:10:43+07
57ab12c7-61a5-45be-99b4-d591a4a2b0f3	019af38f-9084-70b8-be86-664f056e9e6d	QRIS	175000.00	2025-12-06 05:09:00+07	\N	2025-12-06 19:09:58+07	2025-12-06 19:09:58+07
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
46	App\\Models\\User	4	auth-token	0ac95a966e100b14d729befa75f83803e6786b4aeaa3a1136e9108ea0fdd2c66	["*"]	2025-12-08 00:29:28	\N	2025-12-07 20:43:43	2025-12-08 00:29:28
10	App\\Models\\User	3	auth-token	d51a1bc76154bc2b19d261f9baaa4d45f0e9a0fadc1e8b3616c3d68f409c7c6c	["*"]	2025-12-03 21:45:22	\N	2025-12-03 21:30:10	2025-12-03 21:45:22
\.


--
-- Data for Name: receivables; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."receivables" ("id", "order_id", "remaining_amount", "status", "due_date", "created_at", "updated_at") FROM stdin;
740a42e2-dff4-46f2-a4e2-55b4d0117010	019ab9ff-23cc-7064-978b-e932acbffe33	0.00	SETTLED	\N	2025-11-25 14:51:37+07	2025-11-25 14:57:34+07
8c500778-e70d-4cf9-8223-d0933d066e20	019aba2d-6a28-72ef-b18c-8c7a1f7d318e	0.00	SETTLED	\N	2025-11-25 15:42:09+07	2025-11-25 15:42:10+07
50868e56-ba56-40c5-b6ab-e3a1a1067a97	019ac9ab-c3aa-7207-8ad0-0524b80f2762	0.00	SETTLED	\N	2025-11-28 15:54:28+07	2025-11-28 15:57:33+07
37ec7635-2ae7-4cb0-a422-bf8694740511	019ae4af-2b4f-73a8-b133-f860a306ea5a	0.00	SETTLED	\N	2025-12-03 21:47:56+07	2025-12-03 23:46:48+07
5cb4b5f6-dcd3-437b-bb86-982b1744c73c	019ae52d-b3cd-7257-a377-b2c5eb8630c2	0.00	SETTLED	\N	2025-12-04 00:06:08+07	2025-12-04 00:06:09+07
5096b8c6-37f6-49b9-943b-207b1213a04d	019ae4ac-c8b8-71d6-a56f-986362087721	0.00	SETTLED	\N	2025-12-03 21:45:20+07	2025-12-04 00:34:04+07
47d5d96f-af1e-43af-9cad-ffbf72bbfc72	019ae4a1-eee9-7278-9dad-9b8b62abfc04	0.00	SETTLED	\N	2025-12-03 21:33:29+07	2025-12-04 00:42:10+07
60a4519d-1a14-4f00-aaf0-c0e525efd6e4	019ae54f-0561-71ee-afe9-ba6b3e36da21	0.00	SETTLED	\N	2025-12-04 00:42:32+07	2025-12-04 00:42:38+07
9ec9fa92-fdcd-4b9f-b756-93e219cc1aec	019ae54f-ef5b-7231-b71e-5818469d5122	0.00	SETTLED	\N	2025-12-04 00:43:32+07	2025-12-04 00:45:36+07
8867ef0a-49ae-431b-b558-3cc630940fbf	019ae552-85a9-71d2-b3bb-6c0e9dc72e7d	0.00	SETTLED	\N	2025-12-04 00:46:22+07	2025-12-04 00:46:47+07
2decb68e-3c3b-4f25-9e71-4341a032639c	019ae556-5326-715f-80b7-ce7109cd3119	0.00	SETTLED	\N	2025-12-04 00:50:31+07	2025-12-04 00:55:55+07
2fbf5e88-23d1-4c51-8357-f368869aee10	019ae559-4e2c-7015-8de9-7edffd25cb86	0.00	SETTLED	\N	2025-12-04 00:53:46+07	2025-12-04 00:59:58+07
7a191e6f-4317-450d-be37-f5f84a349fff	019ae55b-2d29-7336-bb94-19e2d2f46bdf	0.00	SETTLED	\N	2025-12-04 00:55:49+07	2025-12-04 01:01:01+07
996d3bdb-6a8f-4dc5-846a-7ad077175d02	019ae560-4035-7356-8340-77b46924dd8f	0.00	SETTLED	\N	2025-12-04 01:01:21+07	2025-12-04 01:02:26+07
c62bf4be-1cd1-42b5-bb76-84f6523dfae8	019ae560-5908-72d5-abbc-d29028453a48	0.00	SETTLED	\N	2025-12-04 01:01:28+07	2025-12-04 01:33:46+07
6050f8f7-1e7e-4aab-b209-bc6173d2da80	019ae583-58b3-7200-b75b-fc0fd5a1b57d	125000.00	OPEN	\N	2025-12-04 01:39:41+07	2025-12-04 01:39:41+07
30caeada-3e25-4e7c-a1d8-6050a63fe2e4	019ae5a4-5c9b-7056-a395-2b715e3f3854	0.00	SETTLED	\N	2025-12-04 02:15:45+07	2025-12-04 02:18:17+07
a7406d2e-0b39-4082-ad7e-4dc557f30140	019ae5b4-c8eb-71a1-9037-02655a4401f5	300000.00	OPEN	\N	2025-12-04 02:33:41+07	2025-12-04 18:24:51+07
aaf8908a-f5a3-4a95-b6b5-6e55e0ace292	019ae5a3-ccbf-7226-8cd4-2fa82c64b148	0.00	SETTLED	\N	2025-12-04 02:15:08+07	2025-12-04 18:25:55+07
d7e73b89-3ccf-47de-a11f-e2732cbe2a82	019ae5a5-3cc9-7004-b6db-797b465b84f8	100000.00	PARTIAL	\N	2025-12-04 02:16:42+07	2025-12-04 18:29:43+07
a59237fd-aa78-4d9f-b26a-eab6e35c2ada	019ae94b-75a8-724e-8bf5-1515a82d99d8	100000.00	PARTIAL	\N	2025-12-04 19:17:08+07	2025-12-04 19:17:08+07
f2717552-f726-4e23-8504-6152a71502cb	019ae94b-e3f8-738c-9f34-e08d91ee7699	200000.00	PARTIAL	\N	2025-12-04 19:17:36+07	2025-12-04 19:17:36+07
4dc8979c-de42-430b-a017-dd6e283f62d0	019aea3a-5f8a-70a1-9c9d-054f570702af	125000.00	OPEN	\N	2025-12-04 23:38:05+07	2025-12-04 23:38:05+07
5ce4fafd-2ab0-4026-99b5-661f17293f48	019aea5a-f645-7183-a2e1-3959e5057158	125000.00	OPEN	\N	2025-12-05 00:13:41+07	2025-12-05 00:13:41+07
b2fcb131-98c2-463b-b07e-98b88b292beb	019aed50-ff83-7018-becb-e2dd61cfc781	125000.00	OPEN	\N	2025-12-05 14:01:39+07	2025-12-05 14:01:39+07
8bf779d7-f792-4c1a-9c8e-b1a385b97eab	019aed52-0dec-72c7-b0fd-30eeb63efc55	0.00	SETTLED	\N	2025-12-05 14:02:49+07	2025-12-05 14:03:07+07
0de5689d-64c6-4f2a-90d6-97d89af86db9	019aed5c-18b6-722c-bfb4-6c61f5c50085	125000.00	OPEN	\N	2025-12-05 14:13:47+07	2025-12-05 14:13:47+07
49164c8a-7d64-4ae4-9b81-0dead122c90a	019aed94-0c47-7188-acfd-7ddd7446abc1	0.00	SETTLED	\N	2025-12-05 15:14:54+07	2025-12-05 15:14:54+07
a242b7be-7b0e-49a8-8fe0-3b948ead9690	019aed9c-a6d3-7198-adc0-dc84bb42bd39	125000.00	OPEN	\N	2025-12-05 15:24:17+07	2025-12-05 15:24:17+07
a0e5af96-24f8-4f9c-bf96-392869ba6bc3	019aeda7-3d1f-72c2-a267-17649f860a2d	125000.00	OPEN	\N	2025-12-05 15:35:51+07	2025-12-05 15:35:51+07
edb4d26d-797e-4145-9895-92b557b3a6e1	019aeddb-aa2d-714f-8242-4f97f324c8c5	131250.00	OPEN	\N	2025-12-05 16:33:07+07	2025-12-05 16:33:07+07
978e460d-9ab5-48d8-9641-6a1b9b6f7197	019aedfa-1eed-7024-8eda-dbfe87958274	0.00	SETTLED	\N	2025-12-05 17:06:23+07	2025-12-05 17:10:43+07
1e386766-f8c6-4933-bd27-c5529a5a6458	019aee30-5f04-7344-afd2-e67042f17e25	125000.00	OPEN	\N	2025-12-05 18:05:38+07	2025-12-05 18:05:38+07
23d7e89d-e246-4034-8f25-0cc83458c861	019af38f-9084-70b8-be86-664f056e9e6d	0.00	SETTLED	\N	2025-12-06 19:07:43+07	2025-12-06 19:09:58+07
eb7b2d20-d383-4216-8909-a5e2c02c3694	019aee47-84cc-711f-a089-15a5026b03c5	0.00	SETTLED	\N	2025-12-05 18:30:55+07	2025-12-06 19:30:19+07
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
c5feb508-806a-4b0d-93d9-33b5c424c2f8	Koper	t	2025-12-06 19:16:56	2025-12-06 19:16:56
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
86ef04eb-777b-4fd3-b559-181577178e32	c5feb508-806a-4b0d-93d9-33b5c424c2f8	Cuci Koper	ITEM	500000.00	t	2025-12-06 19:17:18	2025-12-06 19:17:18
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."sessions" ("id", "user_id", "ip_address", "user_agent", "payload", "last_activity") FROM stdin;
4WiiZwKifmc8jECDaLCc4XX8rmW8LQckubtfVZY1	\N	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:145.0) Gecko/20100101 Firefox/145.0	YTozOntzOjY6Il90b2tlbiI7czo0MDoiNHNWV1pzRXdoa21JanFjdGk2anBFNFl2blVEZElRMm5aRXVQSm1ESSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTYyOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvci9yZWNlaXB0LzAxOWFlNWE0LTVjOWItNzA1Ni1hMzk1LTJiNzE1ZTNmMzg1ND9leHBpcmVzPTE3NjQ3OTY2OTcmc2lnbmF0dXJlPWNiM2U3ZTYzNDg3MmUzMDY1YzM5NmU3YjhlODZkZDU0MjcxMTllMDZiMjU4MDk0NzJhNDkwZjQ5MjIyYjczYzYiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19	1764790241
9Pc1PdVmMOtpUzkDQPPOvh8rxReP0IVC45IKVFh6	\N	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:145.0) Gecko/20100101 Firefox/145.0	YTozOntzOjY6Il90b2tlbiI7czo0MDoiYnpUeHA1ZUVqM3JKVW5zZ1FQRkN2alpkb25hUXBGYk43WGxoaERJVyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTYyOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvci9yZWNlaXB0LzAxOWFlNWE1LTNjYzktNzAwNC1iNmRiLTc5N2I0NjViODRmOD9leHBpcmVzPTE3NjQ4NTc1NDImc2lnbmF0dXJlPTBkZjNjNTExMjE1MGMwNmYyYTEzNDM0MDVlNTMyMjIwNzI3ZjJmMjk0Yzg5NDcxNzI1NDgwYWUwN2NlYzNjMDAiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19	1764850378
ji1sqXf3G9rwV4I4jkwDB1iROkXT2s49Xy1wRwnh	\N	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:145.0) Gecko/20100101 Firefox/145.0	YTozOntzOjY6Il90b2tlbiI7czo0MDoiaERlek9TUEVDNDlyWlBxNXMyeVB0aEVWeGlnMkxuQmlFQ1k4aDRSWCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTYyOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvci9yZWNlaXB0LzAxOWFmMzhmLTkwODQtNzBiOC1iZTg2LTY2NGYwNTZlOWU2ZD9leHBpcmVzPTE3NjUwMzAwNjYmc2lnbmF0dXJlPWVjNmIyMGE3Yjc4MWRiNWZmZGNhMGI5MDIzOWMwYmMyYjczYjU1ZmY5NTA4MzA1ZGFjZGEzMGMxMGJiMjk1OTAiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19	1765024264
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."users" ("id", "name", "email", "email_verified_at", "password", "remember_token", "created_at", "updated_at", "is_active", "branch_id", "username") FROM stdin;
1	Superadmin	superadmin@gmail.com	\N	$2y$12$XbBsF/N8jd2xCFnPnavMvuwVJ4bo7gkL0KjxbfNyrgu4gT0lSNHQq	\N	2025-11-25 14:29:33	2025-11-25 14:29:33	t	\N	superadmin
2	Admin Cabang	admincabang@gmail.com	\N	$2y$12$fM7ladlS0IiQc8zwcObkcOU2VoM5RRcXJq1Ir3qJ9.PheQWq/wPwy	\N	2025-11-25 14:29:33	2025-11-25 14:29:33	t	71adee1b-91d7-43cc-a712-9eaac873c6a5	admincabang
3	Kasir	kasir@gmail.com	\N	$2y$12$nSxty7PQT4znogW351TdjOD0z4K6CWnclNuCB7qMtFCkP2m1cuOZK	\N	2025-11-25 14:29:33	2025-11-25 14:29:33	t	71adee1b-91d7-43cc-a712-9eaac873c6a5	kasir
4	Petugas Cuci	petugascuci@gmail.com	\N	$2y$12$rx4se8DkoqdSNzXVToFggef5vnUr5K22LT4J.aZ/o3jxJ4Mw5I.Ue	\N	2025-11-25 14:29:33	2025-11-25 14:29:33	t	71adee1b-91d7-43cc-a712-9eaac873c6a5	petugascuci
5	Kurir	kurir@gmail.com	\N	$2y$12$IHhqywfyAqaKN3FN.bCHZ.y3/SZBL1x5hOfLXibpaF85NgyrmRR3.	\N	2025-11-25 14:29:34	2025-11-25 14:29:34	t	71adee1b-91d7-43cc-a712-9eaac873c6a5	kurir
6	galuh	galuhdwic@gmail.com	\N	$2y$12$kSKSrZRI1MjRKkO4pIKjFOo3K8tRrZElpwHFag0kyGMfUAhN6XaQe	\N	2025-12-05 01:32:32	2025-12-05 01:52:51	t	71adee1b-91d7-43cc-a712-9eaac873c6a5	galuhdwic
\.


--
-- Data for Name: vouchers; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."vouchers" ("id", "branch_id", "code", "type", "value", "start_at", "end_at", "min_total", "usage_limit", "active", "created_at", "updated_at") FROM stdin;
019ac9ab-60d7-70e3-a1bd-7df8098dc814	71adee1b-91d7-43cc-a712-9eaac873c6a5	NYOBA01	NOMINAL	20000.00	\N	\N	0.00	10	t	2025-11-28 15:54:03+07	2025-11-28 15:54:03+07
\.


--
-- Data for Name: wash_note_items; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."wash_note_items" ("id", "wash_note_id", "order_id", "qty", "process_status", "started_at", "finished_at", "note", "created_at", "updated_at") FROM stdin;
6312061e-acdc-4d62-8b2d-20b512b3dcf9	13afc620-da1b-4f21-aa85-40f830b49f95	019aee47-84cc-711f-a089-15a5026b03c5	3.00	FINISHING	12:32:00	21:34:00	\N	2025-12-08 00:29:25	2025-12-08 00:29:25
\.


--
-- Data for Name: wash_notes; Type: TABLE DATA; Schema: public; Owner: -
--

COPY "public"."wash_notes" ("id", "user_id", "branch_id", "note_date", "orders_count", "total_qty", "created_at", "updated_at") FROM stdin;
13afc620-da1b-4f21-aa85-40f830b49f95	4	71adee1b-91d7-43cc-a712-9eaac873c6a5	2025-12-07	1	3.00	2025-12-07 17:58:11	2025-12-07 17:58:11
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

SELECT pg_catalog.setval('"public"."migrations_id_seq"', 35, true);


--
-- Name: permissions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('"public"."permissions_id_seq"', 1, false);


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('"public"."personal_access_tokens_id_seq"', 46, true);


--
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('"public"."roles_id_seq"', 5, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('"public"."users_id_seq"', 6, true);


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

\unrestrict MOxdsBzcoQPDsg8Z8nUryA0qO4xcb1FjqOCoQVTRYoJj9dzi3KI6S2FeibmiK3u

