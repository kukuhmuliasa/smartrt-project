{{-- resources/views/chatbot/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('SmartRT Chatbot Assistant') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    {{-- Area Chat --}}
                    <div id="chat-window" class="h-96 overflow-y-auto mb-4 p-3 border dark:border-gray-700 rounded space-y-4">
                        {{-- Pesan Awal Bot --}}
                        <div class="flex">
                            <div class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 p-3 rounded-lg max-w-xs lg:max-w-md">
                                <p>Halo! Saya adalah SmartRT Assistant. Ada yang bisa saya bantu?</p>
                            </div>
                        </div>
                    </div>

                    {{-- Input Pesan --}}
                    <form id="chat-form" class="flex gap-2">
                        <x-text-input type="text" id="message" name="message" class="flex-grow" placeholder="Ketik pesan Anda..." autocomplete="off" />
                        <x-primary-button type="submit" id="send-button">
                            {{ __('Kirim') }}
                        </x-primary-button>
                    </form>
                    <div id="typing-indicator" class="text-sm text-gray-500 dark:text-gray-400 mt-2" style="display: none;">
                        SmartRT Assistant sedang mengetik...
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message');
        const chatWindow = document.getElementById('chat-window');
        const sendButton = document.getElementById('send-button');
        const typingIndicator = document.getElementById('typing-indicator');
        let conversationHistory = [];

        conversationHistory.push({
            role: 'assistant',
            content: 'Halo! Saya adalah SmartRT Assistant. Ada yang bisa saya bantu?'
        });

        if (!chatForm) {
            console.error('Elemen form dengan ID "chat-form" tidak ditemukan!');
            return;
        }

        chatForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const userMessage = messageInput.value.trim();

            if (userMessage === '') {
                return;
            }

            appendMessage(userMessage, 'user');
            conversationHistory.push({ role: 'user', content: userMessage });
            messageInput.value = '';
            if(sendButton) sendButton.disabled = true;
            if(typingIndicator) typingIndicator.style.display = 'block';

            try {
                const response = await fetch("{{ route('chatbot.send') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        message: userMessage,
                        history: conversationHistory.slice(-6)
                    })
                });

                if (!response.ok) {
                    let errorData = { error: 'HTTP error! status: ${response.status}' };
                    try {
                        errorData = await response.json();
                    } catch (parseError) {
                        // Biarkan errorData default jika parse gagal
                    }
                    // PERBAIKAN DI SINI: Gunakan backtick untuk template literal
                    throw new Error(errorData.error || 'HTTP error! status: ${response.status}');
                }

                const data = await response.json();
                appendMessage(data.reply, 'assistant');
                conversationHistory.push({ role: 'assistant', content: data.reply });

            } catch (error) {
                console.error('Error:', error);
                // PERBAIKAN DI SINI: Gunakan backtick untuk template literal
                appendMessage(`Error: ${error.message || 'Tidak bisa mendapatkan balasan.'}`, 'error');
            } finally {
                if(sendButton) sendButton.disabled = false;
                if(typingIndicator) typingIndicator.style.display = 'none';
                if(messageInput) messageInput.focus();
            }
        });

        function appendMessage(message, sender) {
            if (!chatWindow) return;
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('flex');
            const bubbleDiv = document.createElement('div');
            bubbleDiv.classList.add('p-3', 'rounded-lg', 'max-w-xs', 'lg:max-w-md');
            if (sender === 'user') {
                messageDiv.classList.add('justify-end');
                bubbleDiv.classList.add('bg-blue-500', 'dark:bg-blue-600', 'text-white');
            } else if (sender === 'assistant') {
                bubbleDiv.classList.add('bg-gray-200', 'dark:bg-gray-700', 'text-gray-800', 'dark:text-gray-200');
            } else { // error
                 bubbleDiv.classList.add('bg-red-200', 'dark:bg-red-700', 'text-red-800', 'dark:text-red-200');
            }
            const pre = document.createElement('pre');
            pre.style.whiteSpace = 'pre-wrap';
            pre.style.fontFamily = 'inherit';
            pre.textContent = message;
            bubbleDiv.appendChild(pre);
            messageDiv.appendChild(bubbleDiv);
            chatWindow.appendChild(messageDiv);
            chatWindow.scrollTop = chatWindow.scrollHeight;
        }
    });
</script>
@endpush
</x-app-layout>