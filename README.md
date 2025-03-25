# Semantic Project Search System

An AI-powered search solution developed during my internship at NED's CIS Department to enhance project discovery for students.

## Project Overview

**Challenge:** Traditional database searches limit students' ability to find relevant academic projects effectively.

**Solution:** A semantic search system that understands query context beyond exact keyword matching.

## Technology Stack

- **Frontend:** HTML, CSS, JavaScript, Node.js
- **Backend Database Interaction:** PHP
- **AI Search Engine:** Python (FastAPI, Sentence Transformers)
- **Database:** MySQL

## My Contributions

I focused on two critical components:
1. **PHP Database Interaction**
   - Developed database query mechanisms
   - Created API endpoints for fetching project data
   - Implemented search result retrieval

2. **Python AI Search Backend**
   - Designed semantic search algorithm using Hugging Face Sentence Transformers
   - Built FastAPI endpoints for processing search queries
   - Implemented similarity scoring and ranking logic

## Key Features

- Semantic understanding of search queries
- Contextual project recommendations
- Fast and efficient search processing
- Cross-technology integration

## Technical Architecture

```
Frontend (Node.js/HTML/JS) → PHP Backend → Python AI Search → MySQL Database
```

## Installation & Setup

### Prerequisites
- Python 3.8+
- PHP 7.4+
- Node.js
- MySQL

### Backend Setup
```bash
# Python dependencies
pip install -r requirements.txt

# Start FastAPI server
uvicorn main:app --reload
```

## API Endpoints

- `GET /`: Health check
- `POST /search`: Project search endpoint
  ```json
  {
    "query": "Machine Learning",
    "titles": ["Project List"],
    "top_k": 5
  }
  ```


## Project Impact

Enables students to discover more relevant projects by understanding search intent, not just matching keywords.

---

*Developed during internship at National Center in Big Data and Cloud Computing (NED)*
