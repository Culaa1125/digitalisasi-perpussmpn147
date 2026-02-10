<div x-data="chatbotWidget()" x-cloak>
    <!-- Floating Button - Responsive Size -->
    <button 
        @click="toggleChat()"
        class="fixed bottom-4 right-4 md:bottom-6 md:right-6 w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full shadow-2xl hover:shadow-3xl transform hover:scale-110 transition-all duration-300 z-50 flex items-center justify-center group"
        :class="{ 'scale-0': isOpen }"
    >
        <div class="relative">
            <i class="fas fa-robot text-white text-xl md:text-2xl"></i>
            <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full animate-pulse"></span>
        </div>
        <!-- Tooltip - Hidden on Mobile -->
        <div class="hidden md:block absolute right-20 bg-gray-800 text-white px-4 py-2 rounded-lg text-sm whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
            Chat dengan AuraBot 🤖
        </div>
    </button>

    <!-- Chat Window - Mobile Full Screen, Desktop Fixed -->
    <div 
        x-show="isOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 transform translate-y-4 scale-95"
        class="fixed inset-0 md:inset-auto md:bottom-6 md:right-6 md:w-96 md:h-[600px] bg-white md:rounded-2xl shadow-2xl z-50 flex flex-col overflow-hidden"
        style="max-height: 100vh; md:max-height: calc(100vh - 100px);"
    >
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                        <i class="fas fa-robot text-blue-500 text-xl"></i>
                    </div>
                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 border-2 border-white rounded-full"></span>
                </div>
                <div>
                    <h3 class="text-white font-bold text-base md:text-lg">AuraBot</h3>
                    <p class="text-blue-100 text-xs">Asisten Virtual SMPN 147</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button 
                    @click="clearChat()"
                    class="text-white hover:bg-white/20 p-2 rounded-lg transition-colors"
                    title="Clear Chat"
                >
                    <i class="fas fa-trash text-sm"></i>
                </button>
                <button 
                    @click="toggleChat()"
                    class="text-white hover:bg-white/20 p-2 rounded-lg transition-colors"
                >
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Messages Container -->
        <div 
            x-ref="messagesContainer"
            class="flex-1 overflow-y-auto p-3 md:p-4 space-y-3 md:space-y-4 bg-gray-50"
        >
            <!-- Welcome Message -->
            <div x-show="messages.length === 0" class="text-center py-6 md:py-8">
                <div class="w-16 h-16 md:w-20 md:h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full mx-auto mb-4 flex items-center justify-center">
                    <i class="fas fa-robot text-white text-2xl md:text-3xl"></i>
                </div>
                <h4 class="text-gray-800 font-bold text-base md:text-lg mb-2">Halo! Saya AuraBot 👋</h4>
                <p class="text-gray-600 text-xs md:text-sm mb-4 px-4">Asisten virtual untuk membantu Anda di perpustakaan digital SMPN 147 Jakarta</p>
                
                <!-- Quick Action Buttons - Stacked on Mobile -->
                <div class="flex flex-col md:flex-row md:flex-wrap gap-2 justify-center px-4">
                    <button 
                        @click="sendQuickMessage('Rekomendasikan buku untuk saya')"
                        class="bg-white border border-gray-300 hover:border-blue-500 px-4 py-2 rounded-full text-xs md:text-sm text-gray-700 hover:text-blue-500 transition-colors w-full md:w-auto"
                    >
                        📚 Rekomendasi Buku
                    </button>
                    <button 
                        @click="sendQuickMessage('Video apa yang populer?')"
                        class="bg-white border border-gray-300 hover:border-blue-500 px-4 py-2 rounded-full text-xs md:text-sm text-gray-700 hover:text-blue-500 transition-colors w-full md:w-auto"
                    >
                        🎬 Video Populer
                    </button>
                    <button 
                        @click="sendQuickMessage('Bagaimana cara menggunakan website ini?')"
                        class="bg-white border border-gray-300 hover:border-blue-500 px-4 py-2 rounded-full text-xs md:text-sm text-gray-700 hover:text-blue-500 transition-colors w-full md:w-auto"
                    >
                        ❓ Cara Pakai
                    </button>
                </div>
            </div>

            <!-- Messages -->
            <template x-for="(msg, index) in messages" :key="index">
                <div :class="msg.type === 'user' ? 'flex justify-end' : 'flex justify-start'">
                    <div :class="msg.type === 'user' ? 'order-2' : 'order-1'" class="max-w-[85%] md:max-w-[80%]">
                        <div 
                            :class="{
                                'bg-gradient-to-r from-blue-500 to-purple-600 text-white': msg.type === 'user',
                                'bg-white text-gray-800 border border-gray-200': msg.type === 'bot'
                            }"
                            class="rounded-2xl px-3 py-2 md:px-4 md:py-3 shadow-sm"
                        >
                            <p class="text-xs md:text-sm leading-relaxed whitespace-pre-wrap wrap-break-word" x-html="formatMessage(msg.message)"></p>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 px-2" x-text="msg.time"></p>
                    </div>
                </div>
            </template>

            <!-- Typing Indicator -->
            <div x-show="isTyping" class="flex justify-start">
                <div class="bg-white border border-gray-200 rounded-2xl px-4 py-3 shadow-sm">
                    <div class="flex gap-1">
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input Area - Touch Optimized -->
        <div class="p-3 md:p-4 bg-white border-t border-gray-200 safe-area-inset-bottom">
            <form @submit.prevent="sendMessage()" class="flex gap-2">
                <input 
                    x-model="currentMessage"
                    type="text"
                    placeholder="Ketik pesan Anda..."
                    class="flex-1 px-3 md:px-4 py-2.5 md:py-3 border border-gray-300 rounded-full focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-sm"
                    :disabled="isTyping"
                >
                <button 
                    type="submit"
                    :disabled="!currentMessage.trim() || isTyping"
                    class="w-11 h-11 md:w-12 md:h-12 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-full flex items-center justify-center hover:shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed flex-shrink-0"
                >
                    <i class="fas fa-paper-plane text-sm"></i>
                </button>
            </form>
            <p class="text-xs text-gray-500 text-center mt-2">
                Powered by Gemini AI ✨
            </p>
        </div>
    </div>

    <!-- Backdrop for Mobile -->
    <div 
        x-show="isOpen"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden"
        @click="toggleChat()"
    ></div>
</div>

<style>
    [x-cloak] { display: none !important; }
    
    @keyframes bounce {
        0%, 80%, 100% { 
            transform: translateY(0);
        }
        40% { 
            transform: translateY(-8px);
        }
    }
    
    .animate-bounce {
        animation: bounce 1.4s infinite;
    }

    /* Safe area for iPhone notch */
    .safe-area-inset-bottom {
        padding-bottom: env(safe-area-inset-bottom);
    }

    /* Prevent body scroll when chat is open on mobile */
    body.chat-open {
        overflow: hidden;
    }

    /* Custom scrollbar */
    .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }

    .overflow-y-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #cbd5e0;
        border-radius: 3px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #a0aec0;
    }
</style>

<script>
function chatbotWidget() {
    return {
        // ===== STATE =====
        isOpen: false,
        currentMessage: '',
        messages: [],
        isTyping: false,
        sessionId: null,

        // ===== LIFECYCLE =====
        init() {
            this.sessionId = this.generateSessionId();
            this.loadHistory();
        },

        // ===== UTILS =====
        generateSessionId() {
            return 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
        },

        // ===== UI =====
        toggleChat() {
            this.isOpen = !this.isOpen;

            document.body.classList.toggle('chat-open', this.isOpen);

            if (this.isOpen) {
                this.$nextTick(() => this.scrollToBottom());
            }
        },

        formatMessage(text) {
            text = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            text = text.replace(/\*(.*?)\*/g, '<em>$1</em>');
            text = text.replace(/\n/g, '<br>');

            const urlRegex = /(https?:\/\/[^\s]+)/g;
            return text.replace(
                urlRegex,
                '<a href="$1" target="_blank" class="text-blue-500 underline">$1</a>'
            );
        },

        // ===== DATA =====
        async loadHistory() {
            try {
                const response = await fetch(
                    `{{ route('siswa.chatbot.history') }}?session_id=${this.sessionId}`,
                    {
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    }
                );

                const data = await response.json();
                if (data.success) {
                    this.messages = data.history;
                    this.$nextTick(() => this.scrollToBottom());
                }
            } catch (e) {
                console.error(e);
            }
        },

        async sendMessage() {
            if (!this.currentMessage.trim()) return;

            const msg = this.currentMessage;

            this.messages.push({
                type: 'user',
                message: msg,
                time: new Date().toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                })
            });

            this.currentMessage = '';
            this.isTyping = true;
            this.scrollToBottom();

            try {
                const res = await fetch('{{ route('siswa.chatbot.chat') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        message: msg,
                        session_id: this.sessionId
                    })
                });

                const data = await res.json();
                this.isTyping = false;

                this.messages.push({
                    type: 'bot',
                    message: data.success ? data.response : 'Terjadi kesalahan.',
                    time: new Date().toLocaleTimeString('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit'
                    })
                });

                this.scrollToBottom();
            } catch (e) {
                this.isTyping = false;
                console.error(e);
            }
        },

        sendQuickMessage(msg) {
            this.currentMessage = msg;
            this.sendMessage();
        },

        async clearChat() {
            if (!confirm('Yakin ingin menghapus semua riwayat chat?')) return;

            this.messages = [];
            this.sessionId = this.generateSessionId();
        },

        scrollToBottom() {
            this.$nextTick(() => {
                this.$refs.messagesContainer.scrollTop =
                    this.$refs.messagesContainer.scrollHeight;
            });
        }
    }
}
</script>