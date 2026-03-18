import discord
import time
import aiomysql
from discord import app_commands, Interaction
from discord.ui import View, Button
from discord.ext import commands
from utils.constants import NexureConstants, cases, blacklists
from utils.utils import NexureContext
from utils.modals import BlacklistModal
from utils.pagination import GuildPaginator

constants = NexureConstants()

async def is_panel_admin(discord_id: int) -> bool:
    
    if not constants.pool:
        await constants.connect()
        
    async with constants.pool.acquire() as conn:
        async with conn.cursor(aiomysql.DictCursor) as cur:
            await cur.execute(
                "SELECT id FROM nexure_users WHERE oAuthID=%s AND accessLevel='Administrator'",
                (discord_id,),
            )
            row = await cur.fetchone()
            return bool(row)


class AdminCommandsCog(commands.Cog):
    def __init__(self, nexure):
        self.nexure = nexure


    @commands.command()
    async def checkguild(self, ctx: NexureContext, guild_id: int):
        await ctx.send_success(f"Checkguild command received for ID: `{guild_id}`")


    @commands.command()
    async def guildlist(self, ctx: NexureContext):
        guilds = sorted(ctx.nexure.guilds, key=lambda g: -g.member_count)
        view = GuildPaginator(ctx, guilds)
        await view.send()


    @app_commands.command(name="blacklist")
    async def blacklist(self, interaction: discord.Interaction, entity_id: str, blacklist_type: str):
        if not constants.pool:
            await constants.connect()

        user = await interaction.client.fetch_user(int(entity_id))
        display_name = f"{user.mention}"

        async with constants.pool.acquire() as conn:
            
            async with conn.cursor(aiomysql.DictCursor) as cur:
                await cur.execute(
                    "SELECT accessLevel FROM nexure_users WHERE oAuthID=%s",
                    (user.id,)
                )
                
                row = await cur.fetchone()

                if row and row.get("accessLevel") == "Administrator":
                    embed = discord.Embed(
                        description=f"{self.nexure.error} You cannot blacklist another **Administrator**.",
                        color=self.nexure.base_color,
                    )
                    return await interaction.response.send_message(embed=embed)

        modal = BlacklistModal(self.nexure, int(entity_id), display_name, blacklist_type)
        await interaction.response.send_modal(modal)
        
        
    @app_commands.command(name="unblacklist")
    async def unblacklist(self, interaction: Interaction, entity_id: str):
        
        await interaction.response.defer(ephemeral=False)

        if not constants.pool:
            await constants.connect()

        try:
            entity_user = await self.nexure.fetch_user(int(entity_id))
            entity_type, entity_id_int, display = "user", entity_user.id, entity_user.mention
            
        except Exception:
            entity_type, entity_id_int, display = "guild", entity_id, f"Guild `{entity_id}`"


        async with constants.pool.acquire() as conn:
            
            async with conn.cursor(aiomysql.DictCursor) as cur:
                await cur.execute(
                    "SELECT * FROM nexure_blacklists WHERE discord_id=%s",
                    (entity_id_int)
                )
                
                row = await cur.fetchone()

                if not row:
                    embed = discord.Embed(
                        description=f"{self.nexure.error} {display} is not actively blacklisted.",
                        color=self.nexure.base_color,
                    )
                    return await interaction.followup.send(embed=embed)

                if entity_type == "user":
                    await cur.execute("SELECT email FROM nexure_users WHERE oAuthID=%s", (entity_id_int,))
                    user_row = await cur.fetchone()
                    if user_row and user_row.get("email"):
                        email = user_row["email"]
                        await cur.execute(
                            """
                            UPDATE nexure_accounts
                            SET accountStatus='Open',
                                accountStatusReason='',
                                paymentStatus='11 - Current Account'
                            WHERE email=%s
                            """,
                            (email,)
                        )
                        await cur.execute(
                            """
                            UPDATE nexure_users
                            SET onlineAccessStatus='Active'
                            WHERE oAuthID=%s
                            """,
                            (entity_id_int,)
                        )

                await cur.execute(
                    """
                    UPDATE nexure_blacklists
                    SET blacklist_status='cleared',
                        blacklist_updated_date=NOW()
                    WHERE discord_id=%s AND blacklist_status='Active'
                    """,
                    (entity_id_int)
                )

                await cur.execute(
                    """
                    UPDATE nexure_cases
                    SET status='cleared'
                    WHERE case_id=%s OR (email IN (SELECT email FROM nexure_users WHERE oAuthID=%s) AND type='blacklist' AND status='active')
                    """,
                    (row["case_id"], entity_id_int)
                )
            await conn.commit()

        embed = discord.Embed(
            description=f"{self.nexure.success} **{display}** has been **unblacklisted**.",
            color=self.nexure.base_color,
        )
        
        await interaction.followup.send(embed=embed)
    
    @commands.command()
    async def sync(self, ctx: NexureContext, guild_id: int = None):
        if guild_id:
            guild = discord.Object(id=guild_id)
            synced = await self.nexure.tree.sync(guild=guild)
        else:
            synced = await self.nexure.tree.sync()
        await ctx.send_success(f"Synced **{len(synced)}** commands.")
        

async def setup(nexure):
    await nexure.add_cog(AdminCommandsCog(nexure))
