@extends('front.layout')

@section('title', 'Privacy Policy - Gift Store')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-gray-700 to-gray-900 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Privacy Policy</h1>
        <p class="text-xl text-gray-300">Last updated: {{ date('F d, Y') }}</p>
    </div>
</div>

<!-- Privacy Policy Content -->
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-lg shadow-sm p-8 lg:p-12 prose prose-lg max-w-none">
        
        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Introduction</h2>
            <p class="text-gray-700 leading-relaxed mb-4">
                Welcome to Gift Store. We respect your privacy and are committed to protecting your personal data. 
                This privacy policy will inform you about how we look after your personal data when you visit our 
                website and tell you about your privacy rights and how the law protects you.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Information We Collect</h2>
            <p class="text-gray-700 leading-relaxed mb-4">
                We may collect, use, store and transfer different kinds of personal data about you which we have 
                grouped together as follows:
            </p>
            <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4">
                <li><strong>Identity Data:</strong> includes first name, last name, username or similar identifier.</li>
                <li><strong>Contact Data:</strong> includes billing address, delivery address, email address and telephone numbers.</li>
                <li><strong>Technical Data:</strong> includes internet protocol (IP) address, browser type and version, time zone setting and location, browser plug-in types and versions, operating system and platform.</li>
                <li><strong>Usage Data:</strong> includes information about how you use our website, products and services.</li>
                <li><strong>Marketing and Communications Data:</strong> includes your preferences in receiving marketing from us and our third parties and your communication preferences.</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">How We Use Your Information</h2>
            <p class="text-gray-700 leading-relaxed mb-4">
                We will only use your personal data when the law allows us to. Most commonly, we will use your 
                personal data in the following circumstances:
            </p>
            <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4">
                <li>To register you as a new customer</li>
                <li>To process and deliver your order</li>
                <li>To manage our relationship with you</li>
                <li>To improve our website, products/services, marketing or customer relationships</li>
                <li>To recommend products or services which may be of interest to you</li>
                <li>To administer and protect our business and website</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Cookies</h2>
            <p class="text-gray-700 leading-relaxed mb-4">
                Our website uses cookies to distinguish you from other users of our website. This helps us to 
                provide you with a good experience when you browse our website and also allows us to improve our site. 
                A cookie is a small file of letters and numbers that we store on your browser or the hard drive of 
                your computer if you agree. Cookies contain information that is transferred to your computer's hard drive.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Third-Party Links</h2>
            <p class="text-gray-700 leading-relaxed mb-4">
                This website may include links to third-party websites, plug-ins and applications. Clicking on those 
                links or enabling those connections may allow third parties to collect or share data about you. We do 
                not control these third-party websites and are not responsible for their privacy statements. When you 
                leave our website, we encourage you to read the privacy policy of every website you visit.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Data Security</h2>
            <p class="text-gray-700 leading-relaxed mb-4">
                We have put in place appropriate security measures to prevent your personal data from being accidentally 
                lost, used or accessed in an unauthorized way, altered or disclosed. In addition, we limit access to 
                your personal data to those employees, agents, contractors and other third parties who have a business 
                need to know. They will only process your personal data on our instructions and they are subject to a 
                duty of confidentiality.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Data Retention</h2>
            <p class="text-gray-700 leading-relaxed mb-4">
                We will only retain your personal data for as long as necessary to fulfil the purposes we collected 
                it for, including for the purposes of satisfying any legal, accounting, or reporting requirements.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Your Legal Rights</h2>
            <p class="text-gray-700 leading-relaxed mb-4">
                Under certain circumstances, you have rights under data protection laws in relation to your personal data:
            </p>
            <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4">
                <li>Request access to your personal data</li>
                <li>Request correction of your personal data</li>
                <li>Request erasure of your personal data</li>
                <li>Object to processing of your personal data</li>
                <li>Request restriction of processing your personal data</li>
                <li>Request transfer of your personal data</li>
                <li>Right to withdraw consent</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Contact Us</h2>
            <p class="text-gray-700 leading-relaxed mb-4">
                If you have any questions about this privacy policy or our privacy practices, please contact us:
            </p>
            <div class="bg-gray-50 p-6 rounded-lg">
                <p class="text-gray-700 mb-2">
                    <strong>Email:</strong> <a href="mailto:privacy@giftstore.com" class="text-indigo-600 hover:text-indigo-700">privacy@giftstore.com</a>
                </p>
                <p class="text-gray-700 mb-2">
                    <strong>Phone:</strong> <a href="tel:+1234567890" class="text-indigo-600 hover:text-indigo-700">+1 (234) 567-890</a>
                </p>
                <p class="text-gray-700">
                    <strong>Address:</strong> 123 Gift Street, New York, NY 10001, United States
                </p>
            </div>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Changes to This Privacy Policy</h2>
            <p class="text-gray-700 leading-relaxed">
                We may update our privacy policy from time to time. We will notify you of any changes by posting 
                the new privacy policy on this page and updating the "Last updated" date at the top of this privacy policy.
            </p>
        </section>

    </div>
</div>
@endsection
