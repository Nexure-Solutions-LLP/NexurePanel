import os
import importlib
import logging
from fastapi import FastAPI
from pydantic import BaseModel
from fastapi.middleware.cors import CORSMiddleware
from langchain_ollama import ChatOllama
from langchain_core.messages import HumanMessage, SystemMessage

logging.basicConfig(level=logging.INFO)

SYSTEM_PROMPT = (
    "You are Emmie, an intelligent, conversational AI assistant. "
    "Your goal is to be helpful, friendly, and informative. "
    "You can respond in natural, human-like language and use tools when needed "
    "to answer questions more accurately. Always explain your reasoning simply when appropriate, "
    "and keep your responses conversational and concise."
)

TOOLS_DIR = "EmmieTools"
tool_functions = {}

for file in os.listdir(TOOLS_DIR):
    if file.endswith(".py") and file != "__init__.py":
        module = importlib.import_module(f"{TOOLS_DIR}.{file[:-3]}")
        if hasattr(module, "TOOLS"):
            for tool in module.TOOLS:
                tool_functions[tool.name] = tool.func

logging.info(f"✅ Loaded tools: {list(tool_functions.keys())}")

llm = ChatOllama(
    model="qwen3:1.7b",
    temperature=0.2
).bind_tools(list(tool_functions.values()))

chat_history = []

def build_messages(user_input: str):
    messages = [SystemMessage(content=SYSTEM_PROMPT)]
    messages.extend(chat_history)
    messages.append(HumanMessage(content=user_input))
    return messages

app = FastAPI()
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_methods=["*"],
    allow_headers=["*"],
)

class Query(BaseModel):
    question: str

@app.post("/ask")
async def ask_emmie(query: Query):
    messages = build_messages(query.question)

    response = llm.invoke(messages)

    chat_history.append(HumanMessage(content=query.question))
    chat_history.append(response)

    return {"response": response.content}

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000)
