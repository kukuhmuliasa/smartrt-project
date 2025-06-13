<?php

return [
    'api_key' => env('GROQ_API_KEY'),
    'api_url' => env('GROQ_API_URL', 'https://api.groq.com/openai/v1/chat/completions'),
    'default_model' => env('GROQ_MODEL', 'llama3-8b-8192'),
];