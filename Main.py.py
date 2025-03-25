#!/usr/bin/env python
# coding: utf-8

# In[ ]:


import nest_asyncio
from fastapi import FastAPI
from sentence_transformers import SentenceTransformer, util
from pydantic import BaseModel
import uvicorn
from threading import Thread

# To allow FastAPI to run inside Jupyter
nest_asyncio.apply()

# Load the model (from your saved local path)
model_path = r"C:\Dataset\ProjectSearchModel"
model = SentenceTransformer(model_path)

# Initialize FastAPI app
app = FastAPI()

# Request model for search
class SearchRequest(BaseModel):
    query: str
    titles: list[str]
    top_k: int = 3

# Health check / root route
@app.get("/")
def read_root():
    return {"message": "API is running! Visit /docs to test the search endpoint."}

# API endpoint
@app.post("/search")
def search_projects(request: SearchRequest):
    query = request.query
    titles = request.titles
    if not titles:
        return {"results": []}

    # Use the model to compute embeddings and similarity scores
    query_embedding = model.encode(query, convert_to_tensor=True)
    title_embeddings = model.encode(titles, convert_to_tensor=True)
    similarities = util.pytorch_cos_sim(query_embedding, title_embeddings)[0]

    # Sort results by similarity score
    sorted_indices = similarities.argsort(descending=True)
    results = []
    for idx in sorted_indices[:5]:
        results.append({
            "title": titles[idx],
            "score": round(similarities[idx].item(), 4)  # Round to 4 decimal places
        })

    return {"results": results}

# Run the API in a separate thread
def run_api():
    uvicorn.run(app, host="localhost", port=8000)

api_thread = Thread(target=run_api)
api_thread.start()

# Access API docs at: http://localhost:8000/docs

