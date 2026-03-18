# ==========================================================================================================
# This software was created by Nexure Solutions LLP.
# This software was created by Nick Derry, Joy Clens, AlexySSH, Alfie Chadd
# ==========================================================================================================

import discord
import os
import requests
import asyncio
import aiomysql
import sentry_sdk
from datetime import datetime
from discord.ext import commands
from utils.constants import NexureConstants
from utils.utils import get_prefix, NexureContext
from cogwatch import watch
from dotenv import load_dotenv

# We use constants.py to specify things like the mysql db connection, prefix
# and default embed color. This also will load basic settings from the ENV file.

constants = NexureConstants()
load_dotenv()

class Nexure(commands.AutoShardedBot):
    def __init__(self, **kwargs):
        super().__init__(**kwargs)
        self.token = None
        self.start_time = datetime.now()
        self.error = os.getenv("NEXURE_SUCCESS_EMOJI")
        self.success = os.getenv("NEXURE_FAIL_EMOJI")
        self.loading = os.getenv("NEXURE_LOADING_EMOJI")
        self.warning = os.getenv("NEXURE_WARNING_EMOJI")
        self.base_color = 0x8dc6f4
        self.context = NexureContext
        self.prefixes = {}


    async def get_context(self, message, *, cls=NexureContext):
        return await super().get_context(message, cls=cls)


    @watch(path="cogs", preload=False)
    async def on_ready(self):
        self.prefixes = {}
        async with constants.pool.acquire() as conn:
            async with conn.cursor(aiomysql.DictCursor) as cur:
                await cur.execute("SELECT guild_id, prefix FROM nexure_discordconfig")
                rows = await cur.fetchall()
                for row in rows:
                    self.prefixes[int(row["guild_id"])] = row["prefix"]

        if constants.nexure_environment_type() == "Development":
            pass

        else:
            await self.change_presence(
                activity=discord.Activity(
                    name=f"nexuresolutions.com",
                    type=discord.ActivityType.watching,
                )
            )

        print(f"{self.user.name} is ready!")


    async def is_owner(self, user: discord.User):
        await constants.fetch_bypassed_users()
        
        if user.id in constants.bypassed_users:
            return True
        
        try:
            async with constants.pool.acquire() as conn:
                async with conn.cursor(aiomysql.DictCursor) as cur:
                    await cur.execute(
                        "SELECT accessLevel FROM nexure_users WHERE oAuthID=%s AND accessLevel='Administrator'",
                        (user.id,)
                    )
                    row = await cur.fetchone()
                    if row:
                        return True
                    
        except Exception:
            pass

        return False


    async def setup_hook(self) -> None:
        await constants.connect()

        for root, _, files in os.walk("./cogs"):
            for file in files:
                if file.endswith(".py"):
                    cog_path = os.path.relpath(os.path.join(root, file), "./cogs")
                    cog_module = cog_path.replace(os.sep, ".")[:-3]
                    await self.load_extension(f"cogs.{cog_module}")

        print("All cogs loaded successfully!")


    async def refresh_blacklist_periodically(self):
        while True:
            await constants.refresh_blacklists()
            await asyncio.sleep(3600)


intents = discord.Intents.default()
intents.message_content = True
intents.members = True


nexure = Nexure(
    command_prefix=get_prefix,
    intents=intents,
    chunk_guilds_at_startup=False,
    help_command=None,
    allowed_mentions=discord.AllowedMentions(
        replied_user=True, everyone=True, roles=True
    ),
    cls=NexureContext,
)


@nexure.before_invoke
async def before_invoke(ctx):
    if ctx.author.id in constants.bypassed_users:
        return
    await global_blacklist_check(ctx)


async def global_blacklist_check(ctx):
    await constants.fetch_blacklisted_users()
    await constants.fetch_blacklisted_guilds()

    if ctx.author.id in constants.blacklisted_user_ids and ctx.command.name != "unblacklist":
        
        em = discord.Embed(
            title="",
            description=f"{nexure.warning} **Blacklisted User** \n\n> You are blacklisted from Nexure, you can either file a dispute by calling `+1 (855)-537-3591` or emailing `disputes@nexuresolutions.com` or wait 10 years for it to be de-listed.",
            color=constants.nexure_embed_color_setup(),
        )

        await ctx.send(embed=em)
        raise commands.CheckFailure("You are blacklisted from using Nexure.")


    # Check if the guild is blacklisted

    if ctx.guild and ctx.guild.id in constants.blacklisted_guild_ids and ctx.command.name != "unblacklist":
        
        em = discord.Embed(
            title="",
            description=f"{nexure.warning} **Blacklisted Guild** \n\n> This server is blacklisted from Nexure , you can either file a dispute by calling `+1 (855)-537-3591` or emailing `disputes@nexuresolutions.com` or wait 10 years for it to be de-listed.",
            color=constants.nexure_embed_color_setup(),
        )
        
        await ctx.send(embed=em)
        raise commands.CheckFailure("This guild is blacklisted from using Nexure.")

    if ctx.guild is None:
        raise commands.NoPrivateMessage("This command cannot be used in private messages.")

    return True


def run():
    sentry_sdk.init(
        dsn=constants.sentry_dsn_setup(),
        traces_sample_rate=1.0,
        profiles_sample_rate=1.0,
    )
    nexure.run(constants.nexure_token_setup())

if __name__ == "__main__":
    run()