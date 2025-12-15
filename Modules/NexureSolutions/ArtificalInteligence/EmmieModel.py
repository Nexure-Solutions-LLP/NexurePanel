import logging
import os
import importlib
from fastapi import FastAPI
from pydantic import BaseModel
from fastapi.middleware.cors import CORSMiddleware
from langchain_ollama import ChatOllama
from langchain.chains.conversation.memory import ConversationBufferMemory
from langchain.experimental.agents import create_agent_executor, load_tools

TOOLS_DIR = "EmmieTools"
tool_functions = {}

for filename in os.listdir(TOOLS_DIR):
    if filename.endswith(".py") and filename != "__init__.py":
        module_name = filename[:-3]
        module = importlib.import_module(f"{TOOLS_DIR}.{module_name}")
        if hasattr(module, "TOOLS"):
            for tool in module.TOOLS:
                tool_functions[tool.name] = tool.func

print(f"✅ Loaded {len(tool_functions)} tools from EmmieTools")

llm = ChatOllama(model="qwen3:1.7b", reasoning=True)
memory = ConversationBufferMemory(memory_key="chat_history", return_messages=True)

agent_executor = create_agent_executor(
    llm=llm,
    tools=load_tools(list(tool_functions.keys())),
    memory=memory,
    verbose=True
)

app = FastAPI()
app.add_middleware(CORSMiddleware, allow_origins=["*"], allow_methods=["*"], allow_headers=["*"])

class Query(BaseModel):
    question: str

@app.post("/ask")
def ask_emmie(query: Query):
    try:
        result = agent_executor.run(query.question)
        return {"response": result}
    except Exception as e:
        return {"response": f"Error: {str(e)}"}

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000)
