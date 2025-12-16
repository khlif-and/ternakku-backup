<section class="w-full bg-white py-24 px-6">

    <div class="max-w-3xl mx-auto text-center mb-16">
        <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
            Frequently Asked Questions
        </h2>
        <p class="text-lg text-gray-600">
            Get answers to your questions and learn about our platform
        </p>
    </div>

    @php
        $faqs = [
            ['q' => 'What is your return policy?', 'a' => 'We accept returns within 30 days of purchase. Items must be unused and in original packaging. Refunds will be issued once we receive and inspect the item.'],
            ['q' => 'How do I track my order?', 'a' => 'Once your order ships, you will receive a tracking number via email. You can use this number on the carrier\'s website to track your package.'],
            ['q' => 'Can I change or cancel my order?', 'a' => 'Orders can be changed or canceled within 24 hours of purchase, provided they have not yet been shipped. Please contact support immediately to request changes.'],
            ['q' => 'How can I contact support?', 'a' => 'You can contact our support team via email at support@example.com, or by using the live chat feature on our website during business hours.']
        ];
    @endphp

    <div class="max-w-3xl mx-auto space-y-4" x-data="{ openIndex: 0 }">
        @foreach ($faqs as $item)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 faq-item">
                <button type="button"
                    @click="openIndex = (openIndex === {{ $loop->index }}) ? null : {{ $loop->index }}"
                    class="w-full flex justify-between items-center px-6 py-5 text-left accordion-header">
                    <div class="text-lg font-bold text-gray-900">
                        {{ $item['q'] }}
                    </div>
                    <div class="text-gray-500 flex-shrink-0 transition-transform duration-300 ease-in-out icon-wrapper"
                         :class="{ 'rotate-180': openIndex === {{ $loop->index }} }">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                </button>
                <div x-show="openIndex === {{ $loop->index }}"
                     x-collapse
                     style="display: none;"
                     class="accordion-content px-6 text-base text-gray-700 leading-relaxed overflow-hidden">
                    <div class="pb-6">
                        {{ $item['a'] }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
