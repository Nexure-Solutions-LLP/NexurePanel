import discord
import platform
from datetime import datetime
from discord import Interaction
from discord.ext import commands
from discord.ui import View, Button, Modal, TextInput
from utils.constants import NexureConstants
from typing import List

constants = NexureConstants()

class SuccessEmbed(discord.Embed):
    def __init__(self, title: str, description: str, **kwargs):
        super().__init__(
            title=title, description=description, color=discord.Color.green(), **kwargs
        )


class ErrorEmbed(discord.Embed):
    def __init__(self, title: str, description: str, **kwargs):
        super().__init__(
            title=title, description=description, color=discord.Color.red(), **kwargs
        )


class MissingArgsEmbed(discord.Embed):
    def __init__(self, param_name):
        super().__init__(
            title="",
            description=f"<:NexureFail:1377202015507054663> Please specify a {param_name}",
            color=discord.Color.red(),
        )


class BadArgumentEmbed(discord.Embed):
    def __init__(self):
        super().__init__(
            title="",
            description="<:NexureFail:1377202015507054663> You provided an incorrect argument type.",
            color=discord.Color.red(),
        )


class ForbiddenEmbed(discord.Embed):
    def __init__(self):
        super().__init__(
            title="",
            description="<:NexureFail:1377202015507054663> I couldn't send you a DM. Please check your DM settings.",
            color=discord.Color.red(),
        )


class MissingPermissionsEmbed(discord.Embed):
    def __init__(self):
        super().__init__(
            title="",
            description="<:NexureFail:1377202015507054663> You don't have the required permissions to run this command.",
            color=discord.Color.red(),
        )


class UserErrorEmbed(discord.Embed):
    def __init__(self, error_id):
        super().__init__(
            title="Something Went Wrong",
            description=f"Please contact [Nexure Support](https://discord.gg/bvVNAzm89a) for support!\nError ID: `{error_id}`",
            color=discord.Color.red(),
        )
        
        
class DeveloperErrorEmbed(discord.Embed):
    def __init__(self, error, ctx, error_id):
        super().__init__(
            title="Something went wrong!",
            description=f"{error}",
            color=discord.Color.red(),
        )
        self.add_field(name="Error ID", value=f"__{error_id}__", inline=True)
        self.add_field(
            name="User", value=f"{ctx.author.mention}(**{ctx.author.id}**)", inline=True
        )
        self.add_field(
            name="Server Info",
            value=f"{ctx.guild.name}(**{ctx.guild.id}**)",
            inline=True,
        )
        self.add_field(
            name="Command",
            value=f"Name: {ctx.command.qualified_name}\nArgs: {ctx.command.params}",
            inline=True,
        )


class AboutEmbed:
    @staticmethod
    def create_info_embed(
        uptime,
        guilds,
        users,
        latency,
        version,
        bot_name,
        bot_icon,
        shards,
        cluster,
        environment,
        command_run_time,
        thumbnail_url,
    ):
        embed = discord.Embed(
            description=(
                "Nexure Solutions is a comprehensive business management and digital solutions provider, dedicated to helping companies achieve their goals in a competitive, digital-first world."
            ),
            color=discord.Color.from_str("#2a2c30"),
        )

        embed.add_field(name="", value=(""), inline=False)

        embed.add_field(
            name="Nexure Information",
            value=(
                f"> **Servers:** `{guilds:,}`\n"
                f"> **Users:** `{users:,}`\n"
                f"> **Uptime:** <t:{int((uptime.timestamp()))}:R>\n"
                f"> **Latency:** `{round(latency * 1000)}ms`"
            ),
            inline=True,
        )

        embed.add_field(
            name="System Information",
            value=(
                f"> **Language:** `Python`\n"
                f"> **Database:** `{version}`\n"
                f"> **Host OS:** `{platform.system()}`\n"
                f"> **Host:** `Nexure Solutions`"
            ),
            inline=True,
        )

        embed.add_field(name="", value=(""), inline=False)

        embed.set_footer(
            text=f"Cluster {cluster} | Shard {shards} | {environment} • {command_run_time}"
        )

        embed.set_author(name=bot_name, icon_url=bot_icon)
        embed.set_thumbnail(url=thumbnail_url)
        return embed



class AboutWithButtons:
    @staticmethod
    def create_view():
        view = View()

        support_server_button = Button(
            label="Support Server",
            emoji="<:NexureChat:1377219689041756310>",
            style=discord.ButtonStyle.primary,
            url="https://discord.gg/bvVNAzm89a",
        )

        view.add_item(support_server_button)

        return view


class PingCommandEmbed:
    @staticmethod
    def create_ping_embed(
        latency: float,
        database_latency: int,
        uptime,
        shard_info: List[dict],
        page: int = 0,
    ):
        embed = discord.Embed(
            title="<:1E:1303180097200586843> Nexure",
            color=constants.nexure_embed_color_setup(),
        )

        if page == 0:
            embed.add_field(
                name="<:settings:1377235960902848593> **Network Information**",
                value=(
                    f"> **Latency:** `{round(latency * 1000)}ms` \n"
                    f"> **Database:** `{'Connected' if database_latency else 'Disconnected'}`\n"
                    f"> **Uptime:** <t:{int(uptime.timestamp())}:R>"
                ),
                inline=False,
            )
        else:
            embed.add_field(name="**Sharding Information**", value="", inline=False)

            start_index = (page - 1) * 5
            end_index = start_index + 5
            shards_to_display = shard_info[start_index:end_index]

            for shard in shards_to_display:
                embed.add_field(
                    name=f"<:clock:1377236028024164352> **Shard {shard['id']}**",
                    value=f"> **Latency:** `{shard['latency']}ms` \n> **Guilds:** `{shard['guilds']}`",
                    inline=False,
                )

        return embed


class PrefixEmbed(discord.Embed):
    def __init__(self, current_prefix: str):
        super().__init__(
            title="",
            description=f"The current prefix for this server is `{current_prefix}`.",
            color=constants.nexure_embed_color_setup(),
        )


class PrefixSuccessEmbed(discord.Embed):
    def __init__(self, new_prefix: str):
        super().__init__(
            title="",
            description=f"<:NexureSuccess:1370202310113886339> Prefix successfully changed to `{new_prefix}`.",
            color=discord.Color.green(),
        )


class PrefixSuccessEmbedNoneChanged(discord.Embed):
    def __init__(self, new_prefix: str):
        super().__init__(
            title="",
            description=f"The current prefix for this server is `{new_prefix}`.",
            color=discord.Color.green(),
        )
