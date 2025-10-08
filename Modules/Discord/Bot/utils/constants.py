import os
import discord
import aiomysql
import random
import string
from dotenv import load_dotenv


load_dotenv()


class NexureConstants:
    def __init__(self):
        self.pool: aiomysql.Pool | None = None
        self.bypassed_users: list[int] = []
        self.blacklists: list[dict] = []
        self.server_blacklists: list[int] = []
        
        
    async def connect(self):
        if self.pool is None:
            self.pool = await aiomysql.create_pool(
                host=os.getenv("SQL_HOST"),
                port=int(os.getenv("SQL_PORT", 3306)),
                user=os.getenv("SQL_USER"),
                password=os.getenv("SQL_PASSWORD"),
                db=os.getenv("SQL_DATABASE"),
                charset='utf8mb4',
                autocommit=True,
            )


    async def close(self):
        if self.pool:
            self.pool.close()
            await self.pool.wait_closed()
            self.pool = None


    async def fetch_bypassed_users(self):
        
        
        if not self.pool:
            await self.connect()
            
            
        try:
            async with self.pool.acquire() as conn:
                async with conn.cursor(aiomysql.DictCursor) as cur:
                    await cur.execute("SELECT discord_id FROM blacklist_bypass")
                    rows = await cur.fetchall()
                    self.bypassed_users = [row["discord_id"] for row in rows]
                    
                    
        except Exception as e:
            print(f"[DB] Error fetching bypassed users: {e}")


    async def is_owner(self, user_id: int) -> bool:
        if not self.bypassed_users:
            await self.fetch_bypassed_users()
        return user_id in self.bypassed_users


    async def get_prefix(self, guild_id: int) -> str:
        if not self.pool:
            await self.connect()
        async with self.pool.acquire() as conn:
            async with conn.cursor(aiomysql.DictCursor) as cur:
                await cur.execute(
                    "SELECT prefix FROM nexure_discordconfig WHERE guild_id=%s",
                    (guild_id,),
                )
                row = await cur.fetchone()
                return row["prefix"] if row else "!"


    async def get_support_roles(self, guild_id: int) -> list[int]:
        if not self.pool:
            await self.connect()
        async with self.pool.acquire() as conn:
            async with conn.cursor(aiomysql.DictCursor) as cur:
                await cur.execute(
                    "SELECT support_roles FROM nexure_discordconfig WHERE guild_id=%s",
                    (guild_id,),
                )
                row = await cur.fetchone()
                if row and row["support_roles"]:
                    return [int(r.strip()) for r in row["support_roles"].split(",")]
                return []


    async def get_transcript_channel(self, guild_id: int) -> int | None:
        if not self.pool:
            await self.connect()
        async with self.pool.acquire() as conn:
            async with conn.cursor(aiomysql.DictCursor) as cur:
                await cur.execute(
                    "SELECT transcript_channel FROM nexure_discordconfig WHERE guild_id=%s",
                    (guild_id,),
                )
                row = await cur.fetchone()
                return int(row["transcript_channel"]) if row and row["transcript_channel"] else None


    async def get_ticket_categories(self, guild_id: int) -> list[int]:
        if not self.pool:
            await self.connect()
        async with self.pool.acquire() as conn:
            async with conn.cursor(aiomysql.DictCursor) as cur:
                await cur.execute(
                    "SELECT ticket_categories FROM nexure_discordconfig WHERE guild_id=%s",
                    (guild_id,),
                )
                row = await cur.fetchone()
                if row and row["ticket_categories"]:
                    return [int(c.strip()) for c in row["ticket_categories"].split(",")]
                return []
            
            
    async def get_mysql_version(self) -> str:
        if not self.pool:
            await self.connect()
        async with self.pool.acquire() as conn:
            async with conn.cursor() as cur:
                await cur.execute("SELECT VERSION()")
                row = await cur.fetchone()
                return row[0] if row else "Unknown"

    
    @staticmethod
    def generate_case_id() -> str:
        block1 = ''.join(random.choices(string.digits, k=7))
        block2 = ''.join(random.choices(string.ascii_uppercase + string.digits, k=5))
        return f"NXRE-{block1}-{block2}"


    def nexure_token_setup(self):
        return str(os.getenv("TOKEN"))


    def nexure_client_id_setup(self):
        return str(os.getenv("CLIENT_ID"))


    def nexure_client_secret_setup(self):
        return str(os.getenv("CLIENT_SECRET"))


    def nexure_redirect_uri_setup(self):
        return str(os.getenv("REDIRECT_URL"))


    def sentry_dsn_setup(self):
        return os.getenv("SENTRY_DSN")


    def nexure_embed_color_setup(self):
        return discord.Color.from_str(os.getenv("COLOUR", "#8dc6f4"))


    def nexure_environment_type(self):
        return os.getenv("ENVIRONMENT", "Development")


    def __init__(self):
        self.pool: aiomysql.Pool | None = None
        self.bypassed_users: list[int] = []
        self.blacklists: list[dict] = []
        self.blacklisted_user_ids: set[int] = set()
        self.server_blacklists: list[int] = []
        self.blacklisted_guild_ids: set[int] = set()


    async def fetch_blacklisted_users(self):
        
        
        if not self.pool:
            await self.connect()
            
            
        try:
            async with self.pool.acquire() as conn:
                async with conn.cursor(aiomysql.DictCursor) as cur:
                    await cur.execute("""
                        SELECT 
                            discord_id,
                            blacklisttitle,
                            blacklistdescription,
                            blacklistdate,
                            blacklistupdateddate,
                            blackliststatus
                        FROM nexure_blacklists
                    """)
                    rows = await cur.fetchall()
                    self.blacklists = rows
                    self.blacklisted_user_ids = {int(r["discord_id"]) for r in rows}
                    
                    
        except Exception as e:
            print(f"[DB] Error fetching blacklisted users: {e}")


    async def fetch_blacklisted_guilds(self):
        
        
        if not self.pool:
            await self.connect()
            
            
        try:
            async with self.pool.acquire() as conn:
                async with conn.cursor(aiomysql.DictCursor) as cur:
                    await cur.execute("SELECT discord_id FROM server_blacklists")
                    rows = await cur.fetchall()
                    self.server_blacklists = [int(r["discord_id"]) for r in rows]
                    self.blacklisted_guild_ids = set(self.server_blacklists)
                    
                    
        except Exception as e:
            print(f"[DB] Error fetching blacklisted guilds: {e}")


    async def refresh_blacklists(self):
        await self.fetch_blacklisted_users()
        await self.fetch_blacklisted_guilds()


class TableProxy:
    def __init__(self, table: str):
        self.table = table

    def __repr__(self):
        return f"<TableProxy table={self.table}>"


prefixes = TableProxy("nexure_discordconfig")
timezones = TableProxy("nexure_timezones")
guild_counters = TableProxy("nexure_guild_counters")
reminder_counters = TableProxy("nexure_reminder_counters")
blacklists = TableProxy("nexure_blacklists")
blacklist_bypass = TableProxy("blacklist_bypass")
cases = TableProxy("nexure_cases")