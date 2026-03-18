import discord
import time
import datetime
import asyncio
import pytz
from datetime import datetime
from discord.ext import commands
from utils.constants import NexureConstants
from utils.embeds import (
    AboutEmbed,
    AboutWithButtons,
    PingCommandEmbed,
    PrefixEmbed,
    PrefixSuccessEmbed,
    PrefixSuccessEmbedNoneChanged,
)
from utils.pagination import PingPaginationView
from utils.utils import NexureContext

constants = NexureConstants()

class CommandsCog(commands.Cog):
    def __init__(self, nexure):
        self.nexure = nexure


    @commands.hybrid_command(
        description="Provides important information about Nexure.",
        with_app_command=True,
        extras={"category": "Other"},
    )
    async def about(self, ctx: NexureContext):
        
        await ctx.defer(ephemeral=True)

        uptime_formatted = f"<t:{int((self.nexure.start_time.timestamp()))}:R>"
        guilds = len(self.nexure.guilds)
        users = sum(g.member_count for g in self.nexure.guilds)
        shards = self.nexure.shard_count or 1
        cluster = 0
        environment = constants.nexure_environment_type() or "Unknown"
        version = await constants.get_mysql_version()
        command_run_time = datetime.now()
        formatted_time = command_run_time.strftime("Today at %I:%M %p UTC")

        embed = AboutEmbed.create_info_embed(
            uptime=self.nexure.start_time,
            guilds=guilds,
            users=users,
            latency=self.nexure.latency,
            version=version,
            bot_name=ctx.guild.name,
            bot_icon=ctx.guild.icon,
            shards=shards,
            cluster=cluster,
            environment=environment,
            command_run_time=formatted_time,
            thumbnail_url="https://media.discordapp.net/attachments/1370199512123052033/1377213812947816510/NexureLogoSquare.png",
        )

        view = AboutWithButtons.create_view()
        await ctx.send(embed=embed, view=view)



    async def get_db_latency(self):
        
        try:
            start_time = time.time()
            await constants.ping_db()
            db_latency = round((time.time() - start_time) * 1000)
            return db_latency
        
        except Exception as e:
            print(f"Error measuring DB latency: {e}")
            return -1


    @commands.hybrid_command(name="ping", description="Check the bot's latency, uptime, and shard info.", with_app_command=True, extras={"category": "Other"},)
    async def ping(self, ctx: NexureContext):
        
        latency = self.nexure.latency
        database_latency = await self.get_db_latency()
        uptime = self.nexure.start_time
        shard_info = []
        
        for shard_id, shard in self.nexure.shards.items():
            shard_info.append(
                {
                    "id": shard_id,
                    "latency": round(shard.latency * 1000),
                    "guilds": len([g for g in self.nexure.guilds if g.shard_id == shard_id]),
                }
            )

        embed = PingCommandEmbed.create_ping_embed(
            latency, database_latency, uptime, shard_info, page=0
        )
        
        view = PingPaginationView(self.nexure, latency, database_latency, uptime, shard_info)
        await ctx.send(embed=embed, view=view)


    @commands.hybrid_command(description="Use this command to say things to people using the bot.", with_app_command=True, extras={"category": "General"},)
    @commands.has_permissions(administrator=True)
    async def say(self, ctx, *, message: str):
        
        if ctx.interaction:
            await ctx.send("sent", allowed_mentions=discord.AllowedMentions.none(), ephemeral=True)
            await ctx.channel.send(message, allowed_mentions=discord.AllowedMentions.none())
            
        else:
            await ctx.channel.send(message, allowed_mentions=discord.AllowedMentions.none())
            await ctx.message.delete()


async def setup(nexure):
    await nexure.add_cog(CommandsCog(nexure))