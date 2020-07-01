<?php
$categories = [];
$posts = [];

if (\Schema::hasTable('posts')
    && class_exists(\Corals\Modules\CMS\Models\Page::class)
    && class_exists(\Corals\Modules\CMS\Models\Post::class)
) {
    \Corals\Modules\CMS\Models\Page::updateOrCreate(['slug' => 'home', 'type' => 'page',],
        array(
            'title' => 'Home',
            'slug' => 'home',
            'meta_keywords' => 'home',
            'meta_description' => 'home',
            'content' => '<div id="slider">@slider(marketplace-home-page-slider)</div>',
            'published' => 1,
            'published_at' => '2017-11-16 14:26:52',
            'private' => 0,
            'type' => 'page',
            'template' => 'home',
            'author_id' => 1,
            'deleted_at' => NULL,
            'created_at' => '2017-11-16 16:27:04',
            'updated_at' => '2017-11-16 16:27:07',
        ));
    \Corals\Modules\CMS\Models\Page::updateOrCreate(['slug' => 'about-us', 'type' => 'page'],
        array(
            'title' => 'About Us',
            'slug' => 'about-us',
            'meta_keywords' => 'about us',
            'meta_description' => 'about us',
            'content' => '<!-- Page Content-->
<div class="container padding-bottom-2x mb-2">
    <div class="row align-items-center padding-bottom-2x">
        <div class="col-md-5"><img class="d-block w-270 m-auto" src="/assets/themes/marketplace-master/img/features/01.jpg" alt="Online Shopping"></div>
        <div class="col-md-7 text-md-left text-center">
            <div class="mt-30 hidden-md-up"></div>
            <h2>Search, Select, Buy Online.</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam id purus at risus pellentesque faucibus
                a quis eros. In eu fermentum leo. Integer ut eros lacus. Proin ut accumsan leo. Morbi vitae est eget
                dolor consequat aliquam eget quis dolor. Mauris rutrum fermentum erat, at euismod lorem pharetra nec.
                Duis erat lectus, ultrices euismod sagittis at, pharetra eu nisl. Phasellus id ante at velit tincidunt
                hendrerit. Aenean dolor dolor, tristique nec placerat nec.</p><a
                class="text-medium text-decoration-none" href="https://codecanyon.net/user/corals-io/portfolio" target="_blank">View Products&nbsp;<i
                class="icon-arrow-right"></i></a>
        </div>
    </div>
    <hr>
    <div class="row align-items-center padding-top-2x padding-bottom-2x">
        <div class="col-md-5 order-md-2"><img class="d-block w-270 m-auto" src="/assets/themes/marketplace-master/img/features/02.jpg" alt="Delivery">
        </div>
        <div class="col-md-7 order-md-1 text-md-left text-center">
            <div class="mt-30 hidden-md-up"></div>
            <h2>Fast Delivery Worldwide.</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam id purus at risus pellentesque faucibus
                a quis eros. In eu fermentum leo. Integer ut eros lacus. Proin ut accumsan leo. Morbi vitae est eget
                dolor consequat aliquam eget quis dolor. Mauris rutrum fermentum erat, at euismod lorem pharetra nec.
                Duis erat lectus, ultrices euismod sagittis at, pharetra eu nisl. Phasellus id ante at velit tincidunt
                hendrerit. Aenean dolor dolor, tristique nec placerat nec.</p><a
                class="text-medium text-decoration-none" href="https://codecanyon.net/user/corals-io/portfolio" target="_blank">View Products&nbsp;<i
                class="icon-arrow-right"></i></a>
        </div>
    </div>
    <hr>
    <div class="row align-items-center padding-top-2x padding-bottom-2x">
        <div class="col-md-5"><img class="d-block w-270 m-auto" src="/assets/themes/marketplace-master/img/features/03.jpg" alt="Mobile App"></div>
        <div class="col-md-7 text-md-left text-center">
            <div class="mt-30 hidden-md-up"></div>
            <h2>Great Mobile App. Shop On The Go.</h2>
            <p class="mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam id purus at risus
                pellentesque faucibus a quis eros. In eu fermentum leo. Integer ut eros lacus. Proin ut accumsan leo.
                Morbi vitae est eget dolor consequat aliquam eget quis dolor.</p><a class="market-button apple-button"
                                                                                    href="#"><span class="mb-subtitle">Download on the</span><span
                class="mb-title">App Store</span></a><a class="market-button google-button" href="#"><span
                class="mb-subtitle">Download on the</span><span class="mb-title">Google Play</span></a><a
                class="market-button windows-button" href="#"><span class="mb-subtitle">Download on the</span><span
                class="mb-title">Windows Store</span></a>
        </div>
    </div>
    <hr>
    <div class="row align-items-center padding-top-2x padding-bottom-2x">
        <div class="col-md-5 order-md-2"><img class="d-block w-270 m-auto" src="/assets/themes/marketplace-master/img/features/04.jpg" alt="Delivery">
        </div>
        <div class="col-md-7 order-md-1 text-md-left text-center">
            <div class="mt-30 hidden-md-up"></div>
            <h2>Shop Offline. Cozy Outlet Stores.</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam id purus at risus pellentesque faucibus
                a quis eros. In eu fermentum leo. Integer ut eros lacus. Proin ut accumsan leo. Morbi vitae est eget
                dolor consequat aliquam eget quis dolor. Mauris rutrum fermentum erat, at euismod lorem pharetra nec.
                Duis erat lectus, ultrices euismod sagittis at, pharetra eu nisl. Phasellus id ante at velit tincidunt
                hendrerit. Aenean dolor dolor, tristique nec placerat nec.</p><a
                class="text-medium text-decoration-none" href="https://codecanyon.net/user/corals-io/portfolio" target="_blank">View Products&nbsp;<i
                class="icon-arrow-right"></i></a>
        </div>
    </div>
    <hr>
    <div class="text-center padding-top-3x mb-30">
        <h2>Our Core Team</h2>
        <p class="text-muted">People behind your awesome shopping experience.</p>
    </div>
    <div class="row">
        <div class="col-md-3 col-sm-6 mb-30 text-center"><img
                class="d-block w-150 mx-auto img-thumbnail rounded-circle mb-2" src="/assets/themes/marketplace-master/img/team/01.jpg" alt="Team">
            <h6>Grace Wright</h6>
            <p class="text-muted mb-2">Founder, CEO</p>
            <div class="social-bar"><a class="social-button shape-circle sb-facebook" href="#" data-toggle="tooltip"
                                       data-placement="top" title="Facebook"><i class="socicon-facebook"></i></a><a
                    class="social-button shape-circle sb-twitter" href="#" data-toggle="tooltip" data-placement="top"
                    title="Twitter"><i class="socicon-twitter"></i></a><a
                    class="social-button shape-circle sb-google-plus" href="#" data-toggle="tooltip"
                    data-placement="top" title="Google +"><i class="socicon-googleplus"></i></a></div>
        </div>
        <div class="col-md-3 col-sm-6 mb-30 text-center"><img
                class="d-block w-150 mx-auto img-thumbnail rounded-circle mb-2" src="/assets/themes/marketplace-master/img/team/02.jpg" alt="Team">
            <h6>Taylor Jackson</h6>
            <p class="text-muted mb-2">Financial Director</p>
            <div class="social-bar"><a class="social-button shape-circle sb-skype" href="#" data-toggle="tooltip"
                                       data-placement="top" title="Skype"><i class="socicon-skype"></i></a><a
                    class="social-button shape-circle sb-facebook" href="#" data-toggle="tooltip" data-placement="top"
                    title="Facebook"><i class="socicon-facebook"></i></a><a class="social-button shape-circle sb-paypal"
                                                                            href="#" data-toggle="tooltip"
                                                                            data-placement="top" title="PayPal"><i
                    class="socicon-paypal"></i></a></div>
        </div>
        <div class="col-md-3 col-sm-6 mb-30 text-center"><img
                class="d-block w-150 mx-auto img-thumbnail rounded-circle mb-2" src="/assets/themes/marketplace-master/img/team/03.jpg" alt="Team">
            <h6>Quinton Cross</h6>
            <p class="text-muted mb-2">Marketing Director</p>
            <div class="social-bar"><a class="social-button shape-circle sb-twitter" href="#" data-toggle="tooltip"
                                       data-placement="top" title="Twitter"><i class="socicon-twitter"></i></a><a
                    class="social-button shape-circle sb-google-plus" href="#" data-toggle="tooltip"
                    data-placement="top" title="Google +"><i class="socicon-googleplus"></i></a><a
                    class="social-button shape-circle sb-email" href="#" data-toggle="tooltip" data-placement="top"
                    title="Email"><i class="socicon-mail"></i></a></div>
        </div>
        <div class="col-md-3 col-sm-6 mb-30 text-center"><img
                class="d-block w-150 mx-auto img-thumbnail rounded-circle mb-2" src="/assets/themes/marketplace-master/img/team/04.jpg" alt="Team">
            <h6>Liana Mullen</h6>
            <p class="text-muted mb-2">Lead Designer</p>
            <div class="social-bar"><a class="social-button shape-circle sb-behance" href="#" data-toggle="tooltip"
                                       data-placement="top" title="Behance"><i class="socicon-behance"></i></a><a
                    class="social-button shape-circle sb-dribbble" href="#" data-toggle="tooltip" data-placement="top"
                    title="Dribbble"><i class="socicon-dribbble"></i></a><a
                    class="social-button shape-circle sb-instagram" href="#" data-toggle="tooltip" data-placement="top"
                    title="Instagram"><i class="socicon-instagram"></i></a></div>
        </div>
    </div>
</div>',
            'published' => 1,
            'published_at' => '2017-11-16 11:56:34',
            'private' => 0,
            'type' => 'page',
            'template' => 'full',
            'author_id' => 1,
            'deleted_at' => NULL,
            'created_at' => '2017-11-16 11:56:34',
            'updated_at' => '2017-11-16 11:56:34',
        ));
    \Corals\Modules\CMS\Models\Page::updateOrCreate(['slug' => 'blog', 'type' => 'page'],
        array(
            'title' => 'Blog',
            'slug' => 'blog',
            'meta_keywords' => 'Blog',
            'meta_description' => 'Blog',
            'content' => '',
            'published' => 1,
            'published_at' => '2017-11-16 11:56:34',
            'private' => 0,
            'type' => 'page',
            'template' => 'left',
            'author_id' => 1,
            'deleted_at' => NULL,
            'created_at' => '2017-11-16 11:56:34',
            'updated_at' => '2017-11-16 11:56:34',
        ));
    \Corals\Modules\CMS\Models\Page::updateOrCreate(['slug' => 'pricing', 'type' => 'page'],
        array(
            'title' => 'Pricing',
            'meta_keywords' => 'Pricing',
            'meta_description' => 'Pricing',
            'content' => '',
            'published' => 1,
            'published_at' => '2017-11-16 11:56:34',
            'private' => 0,
            'type' => 'page',
            'status' => 'inactive',
            'template' => 'full',
            'author_id' => 1,
            'deleted_at' => NULL,
            'created_at' => '2017-11-16 11:56:34',
            'updated_at' => '2017-11-16 11:56:34',
        ));
    \Corals\Modules\CMS\Models\Page::updateOrCreate(['slug' => 'contact-us', 'type' => 'page'],
        array(
            'title' => 'Contact Us',
            'slug' => 'contact-us',
            'meta_keywords' => 'Contact Us',
            'meta_description' => 'Contact Us',
            'content' => '<div class="row">
            <div class="col">
                <div class="text-center">
                    <h3>Drop Your Message here</h3>
                    <p>You can contact us with anything related to Laraship. <br/> We\'ll get in touch with you as soon as
                        possible.</p>
                </div>
            </div>
        </div>',
            'published' => 1,
            'published_at' => '2017-11-16 11:56:34',
            'private' => 0,
            'type' => 'page',
            'template' => 'contact',
            'author_id' => 1,
            'deleted_at' => NULL,
            'created_at' => '2017-11-16 11:56:34',
            'updated_at' => '2017-11-16 11:56:34',
        ));

    $posts[] = \Corals\Modules\CMS\Models\Post::updateOrCreate(['slug' => 'subscription-commerce-trends-for-2018', 'type' => 'post'],
        array(
            'title' => 'Subscription Commerce Trends for 2018',
            'meta_keywords' => NULL,
            'meta_description' => NULL,
            'content' => '<p>Subscription commerce is ever evolving. A few years ago, who would have expected&nbsp;<a href="https://techcrunch.com/2017/10/10/porsche-launches-on-demand-subscription-for-its-sports-cars-and-suvs/" target="_blank">Porsche</a>&nbsp;to launch a subscription service? Or that monthly boxes of beauty samples or shaving supplies and&nbsp;<a href="https://www.pymnts.com/subscription-commerce/2017/how-over-the-top-services-came-out-on-top/" target="_blank">OTT services</a>&nbsp;would propel the subscription model to new heights? And how will these trends shape the subscription space going forward&mdash;and drive growth and innovation?</p>

<p>Regardless of your billing model, there&rsquo;s an opportunity for you to capitalize on many of the current trends in subscription commerce&mdash;trends that will help you to continue to compete and succeed in your industry.</p>

<h3><strong>What are these trends and how can you learn more?</strong></h3>

<p>These trends are outlined in our &ldquo;Top Ten Trends for 2018&rdquo; which we publish every year to help subscription businesses understand the drivers which may impact them in 2018 and beyond.</p>

<p>One trend, for example, is machine learning and data science which the payments industry is increasingly utilizing to deliver more powerful results for their customers.</p>

<p>Another trend which is driving new revenue is the adoption of a hybrid billing model&mdash; subscription businesses seamlessly sell one-time items and &lsquo;traditional&rsquo; businesses add a subscription component as a means to introduce a new revenue stream.</p>

<p>And while subscriber acquisition is not a new trend, there are some sophisticated ways to acquire new customers that subscription businesses are putting to work for increasingly positive effect.</p>

<p>Download this year&rsquo;s edition and see how these trends and insights can help your subscription business succeed in 2018.</p>

<p>&nbsp;</p>',
            'published' => 1,
            'published_at' => '2017-12-04 11:18:23',
            'private' => 0,
            'type' => 'post',
            'template' => NULL,
            'author_id' => 1,
            'deleted_at' => NULL,
            'created_at' => '2017-12-03 23:45:51',
            'updated_at' => '2017-12-04 13:18:23',
        ));
    $posts[] = \Corals\Modules\CMS\Models\Post::updateOrCreate(['slug' => 'using-machine-learning-to-optimize-subscription-billing', 'type' => 'post'],
        array(
            'title' => 'Using Machine Learning to Optimize Subscription Billing',
            'meta_keywords' => NULL,
            'meta_description' => NULL,
            'content' => '<p>As a data scientist at Recurly, my job is to use the vast amount of data that we have collected to build products that make subscription businesses more successful. One way to think about data science at Recurly is as an extended R&amp;D department for our customers. We use a variety of tools and techniques, attack problems big and small, but at the end of the day, our goal is to put all of Recurly&rsquo;s expertise to work in service of your business.</p>

<p>Managing a successful subscription business requires a wide range of decisions. What is the optimum structure for subscription plans and pricing? What are the most effective subscriber acquisition methods? What are the most efficient collection methods for delinquent subscribers? What strategies will reduce churn and increase revenue?</p>

<p>At Recurly, we&#39;re focused on building the most flexible subscription management platform, a platform that provides a competitive advantage for your business. We reduce the complexity of subscription billing so you can focus on winning new subscribers and delighting current subscribers.</p>

<p>Recently, we turned to data science to tackle a big problem for subscription businesses: involuntary churn.</p>

<h3><strong>The Problem: The Retry Schedule</strong></h3>

<p>One of the most important factors in subscription commerce is subscriber retention. Every billing event needs to occur flawlessly to avoid adversely impacting the subscriber relationship or worse yet, to lose that subscriber to churn.</p>

<p>Every time a subscription comes up for renewal, Recurly creates an invoice and initiates a transaction using the customer&rsquo;s stored billing information, typically a credit card. Sometimes, this transaction is declined by the payment processor or the customer&rsquo;s bank. When this happens, Recurly sends reminder emails to the customer, checks with the Account Updater service to see if the customer&#39;s card has been updated, and also attempts to collect payment at various intervals over a period of time defined by the subscription business. The timing of these collection attempts is called the &ldquo;retry schedule.&rdquo;</p>

<p>Our ability to correct and successfully retry these cards prevents lost revenue, positively impacts your bottom line, and increases your customer retention rate.</p>

<p>Other subscription providers typically offer a static, one-size-fits-all retry schedule, or leave the schedule up to the subscription business, without providing any guidance. In contrast, Recurly can use machine learning to craft a retry schedule that is tailored to each individual invoice based on our historical data with hundreds of millions of transactions. Our approach gives each invoice the best chance of success, without any manual work by our customers.</p>

<p>A key component of Recurly&rsquo;s values is to test, learn and iterate. How did we call on those values to build this critical component of the Recurly platform?</p>

<h3><strong>Applying Machine Learning</strong></h3>

<p>We decided to use statistical models that leverage Recurly&rsquo;s data on transactions (hundreds of millions of transactions built up over years from a wide variety of different businesses) to predict which transactions are likely to succeed. Then, we used these models to craft the ideal retry schedule for each individual invoice. The process of building the models is known as machine learning.</p>

<p>The term &quot;machine learning&quot; encompasses many different processes and methods, but at its heart is an effort to go past explicitly programmed logic and allow a computer to arrive at the best logic on its own.</p>

<p>While humans are optimized for learning certain tasks&mdash;like how children can speak a new language after simply listening for a few months&mdash;computers can also be trained to learn patterns. Aggregating hundreds of millions of transactions to look for the patterns that lead to transaction success is a classic machine learning problem.</p>

<p>A typical machine learning project involves gathering data, training a statistical model on that data, and then evaluating the performance of the model when presented with new data. A model is only as good as the data it&rsquo;s trained on, and here we had a huge advantage.</p>',
            'published' => 1,
            'published_at' => '2017-12-04 11:21:25',
            'private' => 0,
            'type' => 'post',
            'template' => NULL,
            'author_id' => 1,
            'deleted_at' => NULL,
            'created_at' => '2017-12-04 13:21:25',
            'updated_at' => '2017-12-04 13:21:25',
        ));
    $posts[] = \Corals\Modules\CMS\Models\Post::updateOrCreate(['slug' => 'why-you-need-a-blog-subscription-landing-page', 'type' => 'post'],
        array(
            'title' => 'Why You Need A Blog Subscription Landing Page',
            'meta_keywords' => NULL,
            'meta_description' => NULL,
            'content' => '<p>Whether subscribing via email or RSS, your site visitor is individually volunteering to add your content to their day; a day that is already crowded with content from emails, texts, voicemails, site content, and even snail mail. &nbsp;</p>

<p>As a business, each time you receive a new blog subscriber, you have received validation or &quot;a vote&quot; that your audience has identified YOUR content as adding value to their day. With each new blog subscriber, your content is essentially being awarded as being highly relevant to conversations your readers are having on a regular basis.&nbsp;</p>

<p>To best promote the content your blog subscribers can expect to receive on an ongoing basis,&nbsp;<strong>consider adding a blog subscription landing page.&nbsp;</strong>This is a quick win that will help your company enhance the blogging subscription experience and help you measure and manage the success of this offer with analytical insight.</p>

<p>Holistically, your goal with this landing page is to provide visitors with a sneak preview of the experience they will receive by becoming a blog subscriber.<strong>&nbsp;Your blog subscription landing page should include:</strong></p>

<ul>
<li><strong>A high-level overview of topics, categories your blog will discuss.&nbsp;&nbsp;</strong>For example, HubSpot&#39;s blog covers &quot;all of the inbound marketing - SEO, Blogging, Social Media, Landing Pages, Lead Generation, and Analytics.&quot;</li>
<li><strong>Insight into &quot;who&quot; your blog will benefit.&nbsp;&nbsp;</strong>Examples may include HR Directors, Financial Business professionals, Animal Enthusiasts, etc.&nbsp; If your blog appeals to multiple personas, feel free to spell this out.&nbsp; This will help assure your visitor that they are joining a group of like-minded individuals who share their interests and goals.&nbsp;&nbsp;</li>
<li><strong>How your blog will help to drive the relevant conversation.&nbsp;</strong>Examples may include &quot;updates on industry events&quot;, &quot;expert editorials&quot;, &quot;insider tips&quot;, etc.&nbsp;&nbsp;</li>
</ul>

<p><strong>To create your blog subscription landing page, consider the following steps:</strong></p>

<p>1) Create your landing page following&nbsp;landing page best practices.&nbsp; Consider the &quot;subscribing to your blog&quot; offer as similar to other offers you promote using Landing Pages.&nbsp;</p>

<p>2) Create a Call To Action button that will link to this landing page.&nbsp; Use this button as a call to action within your blog articles or on other website pages to link to a blog subscription landing page&nbsp;Make sure your CTA button is supercharged!</p>

<p>3)&nbsp;Create a Thank You Page&nbsp;to complete the sign-up experience with gratitude and a follow-up call to action.&nbsp;</p>

<p>4) Measure the success of your blog subscription landing page.&nbsp;Consider the 3 Secrets to Optimizing Landing Page Copy.&nbsp;</p>

<p>For more information on Blogging Success Strategies,&nbsp;check out more Content Camp Resources and recorded webinars.&nbsp;</p>',
            'published' => 1,
            'published_at' => '2017-12-04 11:33:19',
            'private' => 0,
            'type' => 'post',
            'template' => NULL,
            'author_id' => 1,
            'deleted_at' => NULL,
            'created_at' => '2017-12-04 13:31:46',
            'updated_at' => '2017-12-04 13:33:19',
        ));

}

if (\Schema::hasTable('categories') && class_exists(\Corals\Modules\CMS\Models\Category::class)) {
    $categories[] = \Corals\Modules\CMS\Models\Category::updateOrCreate([
        'name' => 'Computers',
        'slug' => 'computers',
    ]);
    $categories[] = \Corals\Modules\CMS\Models\Category::updateOrCreate([
        'name' => 'Smartphone',
        'slug' => 'smartphone',
    ]);
    $categories[] = \Corals\Modules\CMS\Models\Category::updateOrCreate([
        'name' => 'Gadgets',
        'slug' => 'gadgets',
    ]);
    $categories[] = \Corals\Modules\CMS\Models\Category::updateOrCreate([
        'name' => 'Technology',
        'slug' => 'technology',
    ]);
    $categories[] = \Corals\Modules\CMS\Models\Category::updateOrCreate([
        'name' => 'Engineer',
        'slug' => 'engineer',
    ]);
    $categories[] = \Corals\Modules\CMS\Models\Category::updateOrCreate([
        'name' => 'Subscriptions',
        'slug' => 'subscriptions',
    ]);
    $categories[] = \Corals\Modules\CMS\Models\Category::updateOrCreate([
        'name' => 'Billing',
        'slug' => 'billing',
    ]);
}

$posts_media = [
    0 => array(
        'id' => 4,
        'model_type' => 'Corals\\Modules\\CMS\\Models\\Post',
        'collection_name' => 'featured-image',
        'name' => 'subscription_trends',
        'file_name' => 'subscription_trends.png',
        'mime_type' => 'image/png',
        'disk' => 'media',
        'size' => 20486,
        'manipulations' => '[]',
        'custom_properties' => '{"root":"demo"}',
        'order_column' => 6,
        'created_at' => '2017-12-03 23:45:51',
        'updated_at' => '2017-12-03 23:45:51',
    ),
    1 => array(
        'id' => 8,
        'model_type' => 'Corals\\Modules\\CMS\\Models\\Post',
        'collection_name' => 'featured-image',
        'name' => 'machine_learning',
        'file_name' => 'machine_learning.png',
        'mime_type' => 'image/png',
        'disk' => 'media',
        'size' => 32994,
        'manipulations' => '[]',
        'custom_properties' => '{"root":"demo"}',
        'order_column' => 11,
        'created_at' => '2017-12-04 13:21:25',
        'updated_at' => '2017-12-04 13:21:25',
    ),
    2 => array(
        'id' => 9,
        'model_type' => 'Corals\\Modules\\CMS\\Models\\Post',
        'collection_name' => 'featured-image',
        'name' => 'Successful-Blog_Fotolia_102410353_Subscription_Monthly_M',
        'file_name' => 'Successful-Blog_Fotolia_102410353_Subscription_Monthly_M.jpg',
        'mime_type' => 'image/jpeg',
        'disk' => 'media',
        'size' => 182317,
        'manipulations' => '[]',
        'custom_properties' => '{"root":"demo"}',
        'order_column' => 12,
        'created_at' => '2017-12-04 13:33:19',
        'updated_at' => '2017-12-04 13:33:19',
    ),
];

foreach ($posts as $index => $post) {
    $randIndex = rand(0, 6);
    if (isset($categories[$randIndex])) {
        $category = $categories[$randIndex];
        try {
            \DB::table('category_post')->insert(array(
                array(
                    'post_id' => $post->id,
                    'category_id' => $category->id,
                )
            ));
        } catch (\Exception $exception) {
        }
    }

    if (isset($posts_media[$index])) {
        try {
            $posts_media[$index]['model_id'] = $post->id;
            \DB::table('media')->insert($posts_media[$index]);
        } catch (\Exception $exception) {
        }
    }
}

if (class_exists(\Corals\Menu\Models\Menu::class) && \Schema::hasTable('posts')) {
    // seed root menus
    $topMenu = Corals\Menu\Models\Menu::updateOrCreate(['key' => 'frontend_top'], [
        'parent_id' => 0,
        'url' => null,
        'name' => 'Frontend Top',
        'description' => 'Frontend Top Menu',
        'icon' => null,
        'target' => null,
        'order' => 0
    ]);

    $topMenuId = $topMenu->id;

    // seed children menu
    Corals\Menu\Models\Menu::updateOrCreate(['key' => 'home'], [
        'parent_id' => $topMenuId,
        'url' => '/',
        'active_menu_url' => '/',
        'name' => 'Home',
        'description' => 'Home Menu Item',
        'icon' => 'fa fa-home',
        'target' => null,
        'order' => 0
    ]);

    Corals\Menu\Models\Menu::updateOrCreate(['parent_id' => $topMenuId, 'key' => 'shop'], [
        'url' => 'shop',
        'active_menu_url' => 'shop',
        'name' => 'Shop',
        'description' => 'Shop Menu Item',
        'icon' => null,
        'target' => null,
        'order' => 965
    ]);

    Corals\Menu\Models\Menu::updateOrCreate([
        'parent_id' => $topMenuId,
        'key' => null,
        'url' => 'about-us',
        'active_menu_url' => 'about-us',
        'name' => 'About Us',
        'description' => 'About Us Menu Item',
        'icon' => null,
        'target' => null,
        'order' => 970
    ]);

    Corals\Menu\Models\Menu::updateOrCreate([
        'parent_id' => $topMenuId,
        'key' => null,
        'url' => 'blog',
        'active_menu_url' => 'blog',
        'name' => 'Blog',
        'description' => 'Blog Menu Item',
        'icon' => null,
        'target' => null,
        'order' => 980
    ]);
    Corals\Menu\Models\Menu::updateOrCreate([
        'parent_id' => $topMenuId,
        'key' => null,
        'url' => 'pricing',
        'active_menu_url' => 'pricing',
        'name' => 'Pricing',
        'description' => 'Pricing Menu Item',
        'icon' => null,
        'target' => null,
        'order' => 980
    ]);

    Corals\Menu\Models\Menu::updateOrCreate([
        'parent_id' => $topMenuId,
        'key' => null,
        'url' => 'faqs',
        'active_menu_url' => 'faqs',
        'name' => 'FAQs',
        'description' => 'FAQs',
        'icon' => null,
        'target' => null,
        'order' => 970
    ]);

    Corals\Menu\Models\Menu::updateOrCreate([
        'parent_id' => $topMenuId,
        'key' => null,
        'url' => 'contact-us',
        'active_menu_url' => 'contact-us',
        'name' => 'Contact Us',
        'description' => 'Contact Us Menu Item',
        'icon' => null,
        'target' => null,
        'order' => 980
    ]);

    $footerMenu = Corals\Menu\Models\Menu::updateOrCreate(['key' => 'frontend_footer'], [
        'parent_id' => 0,
        'url' => null,
        'name' => 'Frontend Footer',
        'description' => 'Frontend Footer Menu',
        'icon' => null,
        'target' => null,
        'order' => 0
    ]);

    $footerMenuId = $footerMenu->id;

// seed children menu
    Corals\Menu\Models\Menu::updateOrCreate(['key' => 'footer_home'], [
        'parent_id' => $footerMenuId,
        'url' => '/',
        'active_menu_url' => '/',
        'name' => 'Home',
        'description' => 'Home Menu Item',
        'icon' => null,
        'target' => null,
        'order' => 0
    ]);
    Corals\Menu\Models\Menu::updateOrCreate([
        'parent_id' => $footerMenuId,
        'key' => null,
        'url' => 'about-us',
        'active_menu_url' => 'about-us',
        'name' => 'About Us',
        'description' => 'About Us Menu Item',
        'icon' => null,
        'target' => null,
        'order' => 980
    ]);
    Corals\Menu\Models\Menu::updateOrCreate([
        'parent_id' => $footerMenuId,
        'key' => null,
        'url' => 'contact-us',
        'active_menu_url' => 'contact-us',
        'name' => 'Contact Us',
        'description' => 'Contact Us Menu Item',
        'icon' => null,
        'target' => null,
        'order' => 980
    ]);
    Corals\Menu\Models\Menu::updateOrCreate([
        'parent_id' => $footerMenuId,
        'key' => null,
        'url' => 'blog',
        'active_menu_url' => 'blog',
        'name' => 'Blog',
        'description' => 'Blog Menu Item',
        'icon' => null,
        'target' => null,
        'order' => 980
    ]);
}


if (class_exists(\Corals\Modules\Marketplace\Models\Product::class) && \Schema::hasTable('marketplace_products')) {
    ///////////////////////// Shippings
    $shippings = array(
        array(
            'priority' => 1,
            'shipping_method' => 'Shippo',
            'name'=>'Shippo',
            'rate' => '0.00',
            'country' => 'US',
            'description' => NULL
        ),
        array(
            'priority' => 2,
            'shipping_method' => 'FlatRate',
            'rate' => '10.00',
            'country' => NULL,
            'name'=>'Flat Rate',
            'description' => NULL
        ),
    );

    foreach ($shippings as $shipping) {
        \Corals\Modules\Marketplace\Models\Shipping::updateOrCreate([
            'shipping_method' => $shipping['shipping_method'],
            'country' => $shipping['country']
        ], $shipping);
    }
    ///////////////////////// Coupons
    $coupons = array(
        array(
            'id' => 1,
            'code' => 'CORALS-FIXED',
            'type' => 'fixed',
            'uses' => NULL,
            'min_cart_total' => '500.00',
            'max_discount_value' => NULL,
            'value' => '45',
            'start' => '2018-03-01 00:00:00',
            'expiry' => '2022-03-01 00:00:00',
        ),
        array(
            'id' => 2,
            'code' => 'CORALS-PERC',
            'type' => 'percentage',
            'uses' => NULL,
            'min_cart_total' => '500.00',
            'max_discount_value' => NULL,
            'value' => '10',
            'start' => '2018-03-01 00:00:00',
            'expiry' => '2022-03-01 00:00:00',
        ),
    );

    foreach ($coupons as $coupon) {
        \Corals\Modules\Marketplace\Models\Coupon::updateOrCreate(['code' => $coupon['code']], $coupon);
    }

    ///////////////////////// Settings

    $marketplaceSettings = array(
        array(
            'code' => 'marketplace_company_owner',
            'type' => 'TEXT',
            'label' => 'marketplace_company_owner',
            'value' => 'Corals',
            'editable' => 0,
            'hidden' => 1
        ),
        array(
            'code' => 'marketplace_company_name',
            'type' => 'TEXT',
            'label' => 'marketplace_company_name',
            'value' => 'Corals',
            'editable' => 0,
            'hidden' => 1
        ),
        array(
            'code' => 'marketplace_company_street1',
            'type' => 'TEXT',
            'label' => 'marketplace_company_street1',
            'value' => '5543 Aliquet St.',
            'editable' => 0,
            'hidden' => 1
        ),
        array(
            'code' => 'marketplace_company_city',
            'type' => 'TEXT',
            'label' => 'marketplace_company_city',
            'value' => 'Fort Dodge',
            'editable' => 0,
            'hidden' => 1
        ),
        array(
            'code' => 'marketplace_company_state',
            'type' => 'TEXT',
            'label' => 'marketplace_company_state',
            'value' => 'GA',
            'editable' => 0,
            'hidden' => 1
        ),
        array(
            'code' => 'marketplace_company_zip',
            'type' => 'TEXT',
            'label' => 'marketplace_company_zip',
            'value' => '20783',
            'editable' => 0,
            'hidden' => 1
        ),
        array(
            'code' => 'marketplace_company_country',
            'type' => 'TEXT',
            'label' => 'marketplace_company_country',
            'value' => 'USA',
            'editable' => 0,
            'hidden' => 1
        ),
        array(
            'code' => 'marketplace_company_phone',
            'type' => 'TEXT',
            'label' => 'marketplace_company_phone',
            'value' => '(717) 450-4729',
            'editable' => 0,
            'hidden' => 1
        ),
        array(
            'code' => 'marketplace_company_email',
            'type' => 'TEXT',
            'label' => 'marketplace_company_email',
            'value' => 'support@corals.io',
            'editable' => 0,
            'hidden' => 1
        ),
        array(
            'code' => 'marketplace_shipping_weight_unit',
            'type' => 'TEXT',
            'label' => 'marketplace_shipping_weight_unit',
            'value' => 'oz',
            'editable' => 0,
            'hidden' => 1
        ),
        array(
            'code' => 'marketplace_shipping_dimensions_unit',
            'type' => 'TEXT',
            'label' => 'marketplace_shipping_dimensions_unit',
            'value' => 'in',
            'editable' => 0,
            'hidden' => 1
        ),
        array(
            'code' => 'marketplace_shipping_shippo_live_token',
            'type' => 'TEXT',
            'label' => 'marketplace_shipping_shippo_live_token',
            'value' => NULL,
            'editable' => 0,
            'hidden' => 1
        ),
        array(
            'code' => 'marketplace_shipping_shippo_test_token',
            'type' => 'TEXT',
            'label' => 'marketplace_shipping_shippo_test_token',
            'value' => 'shippo_test_b3aab6f5d5ee5fb9e981906a449d74fe2e7bf9eb',
            'editable' => 0,
            'hidden' => 1
        ),
        array(
            'code' => 'marketplace_shipping_shippo_sandbox_mode',
            'type' => 'TEXT',
            'label' => 'marketplace_shipping_shippo_sandbox_mode',
            'value' => 'true',
            'editable' => 0,
            'hidden' => 1
        ),
        array(
            'code' => 'marketplace_tax_calculate_tax',
            'type' => 'TEXT',
            'label' => 'marketplace_tax_calculate_tax',
            'value' => 'true',
            'editable' => 0,
            'hidden' => 1
        ),
        array(
            'code' => 'marketplace_rating_enable',
            'type' => 'BOOLEAN',
            'label' => 'marketplace_rating_enable',
            'value' => 'true',
            'editable' => 0,
            'hidden' => 1
        ),
        array(
            'code' => 'marketplace_wishlist_enable',
            'type' => 'BOOLEAN',
            'label' => 'marketplace_wishlist_enable',
            'value' => 'true',
            'editable' => 0,
            'hidden' => 1
        ),
        array(
            'code' => 'marketplace_appearance_page_limit',
            'type' => 'TEXT',
            'label' => 'marketplace_appearance_page_limit',
            'value' => '15',
            'editable' => 0,
            'hidden' => 1
        ),
        array(
            'code' => 'marketplace_search_title_weight',
            'type' => 'TEXT',
            'label' => 'marketplace_search_title_weight',
            'value' => '3',
            'editable' => 0,
            'hidden' => 1
        ),
        array(
            'code' => 'marketplace_search_content_weight',
            'type' => 'TEXT',
            'label' => 'marketplace_search_content_weight',
            'value' => '1.5',
            'editable' => 0,
            'hidden' => 1
        ),
        array(
            'code' => 'marketplace_search_enable_wildcards',
            'type' => 'TEXT',
            'label' => 'marketplace_search_enable_wildcards',
            'value' => 'true',
            'editable' => 0,
            'hidden' => 1
        ),
    );

    foreach ($marketplaceSettings as $setting) {
        \Corals\Settings\Models\Setting::updateOrCreate(['code' => $setting['code']], $setting);
    }

}

if (class_exists(\Corals\Modules\Slider\Models\Slider::class) && \Schema::hasTable('sliders')) {
    $slider = \Corals\Modules\Slider\Models\Slider::updateOrCreate(['key' => 'marketplace-home-page-slider'],
        array(
            'name' => 'Marketplace Home Page Slider',
            'key' => 'marketplace-home-page-slider',
            'status' => 'active',
            'type' => 'images',
            'init_options' => json_decode('{"items":{"number":"1"},"margin":{"number":"0"},"loop":{"boolean":"false"},"center":{"boolean":"false"},"mouseDrag":{"boolean":"true"},"touchDrag":{"boolean":"true"},"stagePadding":{"number":"0"},"merge":{"boolean":"false"},"mergeFit":{"boolean":"true"},"autoWidth":{"boolean":"false"},"URLhashListener":{"boolean":"false"},"nav":{"boolean":"false"},"rewind":{"boolean":"true"},"navText":{"array":"[\'next\',\'prev\']"},"dots":{"boolean":"true"},"dotsEach":{"number\\/boolean":"false"},"dotData":{"boolean":"false"},"lazyLoad":{"boolean":"false"},"lazyContent":{"boolean":"false"},"autoplay":{"boolean":"true"},"autoplayTimeout":{"number":"3000"},"autoplayHoverPause":{"boolean":"true"},"autoplaySpeed":{"number\\/boolean":"false"},"navSpeed":{"number\\/boolean":"false"},"dotsSpeed":{"number\\/boolean":"false"},"dragEndSpeed":{"number\\/boolean":"false"},"callbacks":{"boolean":"true"},"responsive":{"object":"false"},"video":{"boolean":"false"},"videoHeight":{"number\\/boolean":"false"},"videoWidth":{"number\\/boolean":"false"},"animateOut":{"array\\/boolean":"false"},"animateIn":{"array\\/boolean":"false"}}', true),
        ));
    $slides = array(
        array(
            'name' => 'E-First Slide',
            'content' => '/assets/themes/marketplace-master/img/slider/ecommerce-1.png',
            'slider_id' => $slider->id,
            'status' => 'active',
        ),
        array(
            'name' => 'E-Second Slide',
            'content' => '/assets/themes/marketplace-master/img/slider/ecommerce-2.png',
            'slider_id' => $slider->id,
            'status' => 'active',
        )
    );

    foreach ($slides as $slide) {
        \Corals\Modules\Slider\Models\Slide::updateOrCreate(
            ['slider_id' => $slide['slider_id'], 'name' => $slide['name']],
            $slide
        );
    }
}



if (\Schema::hasTable('cms_blocks') && class_exists(\Corals\Modules\CMS\Models\Block::class)) {
    $block = \Corals\Modules\CMS\Models\Block::updateOrCreate(['name' => 'Home Stores Features', 'key' => 'home-stores-features'], [
        'name' => 'Home Stores Features',
        'key' => 'home-stores-features',
    ]);

    $widgets = array(
        array(
            'title' => 'Free Worldwide Shipping',
            'content' => '<div class="text-center mb-30"><img
                        class="d-block w-90 img-thumbnail rounded-circle mx-auto mb-3"
                        src="/assets/themes/marketplace-master/img/services/01.png"
                        alt="Shipping">
                <h6>Free Worldwide Shipping</h6>
                <p class="text-muted margin-bottom-none">Free shipping for all orders over $100</p>
            </div>',
            'block_id' => $block->id,
            'widget_width' => 3,
            'widget_order' => 0,
            'status' => 'active',
        ),
        array(
            'title' => 'Money Back Guarantee',
            'content' => '<div class="text-center mb-30"><img
                        class="d-block w-90 img-thumbnail rounded-circle mx-auto mb-3"
                        src="/assets/themes/marketplace-master/img/services/02.png"
                        alt="Money Back">
                <h6>Money Back Guarantee</h6>
                <p class="text-muted margin-bottom-none">We return money within 30 days</p>
            </div>',
            'block_id' => $block->id,
            'widget_width' => 3,
            'widget_order' => 1,
            'status' => 'active',
        ),

        array(
            'title' => '24/7 Customer Support',
            'content' => '<div class="text-center mb-30"><img
                        class="d-block w-90 img-thumbnail rounded-circle mx-auto mb-3"
                        src="/assets/themes/marketplace-master/img/services/03.png"
                        alt="Support">
                <h6>24/7 Customer Support</h6>
                <p class="text-muted margin-bottom-none">Friendly 24/7 customer support</p>
            </div>',
            'block_id' => $block->id,
            'widget_width' => 3,
            'widget_order' => 2,
            'status' => 'active',

        ),
        array(
            'title' => 'Secure Online Payment',
            'content' => '<div class="text-center mb-30"><img
                        class="d-block w-90 img-thumbnail rounded-circle mx-auto mb-3"
                        src="/assets/themes/marketplace-master/img/services/04.png"
                        alt="Payment">
                <h6>Secure Online Payment</h6>
                <p class="text-muted margin-bottom-none">We posess SSL / Secure Certificate</p>
            </div>',
            'block_id' => $block->id,
            'widget_width' => 3,
            'widget_order' => 3,
            'status' => 'active',

        ),
    );
    foreach ($widgets as $widget) {
        \Corals\Modules\CMS\Models\Widget::updateOrCreate(
            ['block_id' => $widget['block_id'], 'title' => $widget['title']],
            $widget
        );
    }
}

/**
 * random orders
 * $skus = \Corals\Modules\Marketplace\Models\SKU::get();
 *$statuses = ['pending', 'processing', 'canceled', 'completed'];
 * foreach ($skus as $sku) {
 * $userId = random_int(5, 259);
 * $qt = random_int(1, 4);
 * $items = [];
 *
 * $shippingItems = [
 * [
 * 'amount' => 10.00,
 * 'description' => 'Flat Rate -  Shipping',
 * 'quantity' => 1,
 * 'sku_code' => 'FlatRate|Flat Rate',
 * 'type' => 'Shipping',
 * 'item_options' => null
 * ],
 * [
 * 'amount' => 7.75,
 * 'description' => 'USPS - Parcel Select Shipping',
 * 'quantity' => 1,
 * 'sku_code' => 'Shippo|' . \Str::random(32),
 * 'type' => 'Shipping',
 * 'item_options' => null
 * ],
 * [
 * 'amount' => 4.66,
 * 'description' => 'USPS - First-Class Package/Mail Parcel Shipping',
 * 'quantity' => 1,
 * 'sku_code' => 'Shippo|' . \Str::random(32),
 * 'type' => 'Shipping',
 * 'item_options' => null
 * ]
 * ];
 * $discountItem = [
 * 'amount' => -45,
 * 'description' => 'Discount Coupon',
 * 'quantity' => 1,
 * 'sku_code' => 'CORALS-FIXED',
 * 'type' => 'Discount',
 * 'item_options' => null
 * ];
 * $skuItem = [
 * 'amount' => $sku->price,
 * 'description' => $sku->product->name,
 * 'quantity' => $qt,
 * 'sku_code' => $sku->code,
 * 'type' => 'Product',
 * 'item_options' => '{\"product_options\":[]}'
* ];
 *
 * $items[] = $skuItem;
 *
 * if ($sku->id % 2) {
 * $items[] = $discountItem;
 * }
 *
 * $items[] = $shippingItems[random_int(0, 2)];
 *
 * $amount = 0;
 *
 * foreach ($items as $item) {
 * $amount += $item['amount'] * $item['quantity'];
 * }
 * $orderNum = \Marketplace::createOrderNumber();
 *
 * $orderId = \DB::table('marketplace_orders')->insertGetId([
 * 'amount' => $amount,
 * 'currency' => 'USD',
 * 'order_number' => $orderNum,
 * 'billing' => '{\"status\":\"pending\",\"label_url\":\"\",\"tracking_number\":\"\",\"shipping_address\":{\"address_1\":\"711-2880 Nulla St.\",\"address_2\":\"Cecilia Chapman\",\"type\":\"shipping\",\"city\":\"Mankato\",\"state\":\"MS\",\"zip\":\"96522\",\"country\":\"US\"},\"shipping_provider\":\"FlatRate\",\"selected_shipping\":{\"provider\":\"Flat Rate\",\"service\":\"\",\"currency\":\"USD\",\"amount\":\"10.00\",\"estimated_days\":\"\"}}\', \'{\"billing_address\":{\"address_1\":\"711-2880 Nulla St.\",\"address_2\":\"Cecilia Chapman\",\"type\":\"billing\",\"city\":\"Mankato\",\"state\":\"MS\",\"zip\":\"96522\",\"country\":\"US\"},\"payment_reference\":\"ch_1C8YJrG0x8xKQUt93uHWgRQr\",\"gateway\":\"Stripe\",\"payment_status\":\"paid\"}',
* 'status' => $statuses[random_int(0, 3)],
 * 'user_id' => $userId,
 * 'created_at' => Carbon\Carbon::now()->subDays($userId)->toDateString(),
 * 'updated_at' => Carbon\Carbon::now()->subDays($userId / 2)->toDateString(),
 * ]);
 *
 * foreach ($items as $index => $item) {
 * $items[$index]['order_id'] = $orderId;
 * }
 *
 * \DB::table('marketplace_order_items')->insert($items);
 * }
 *
 *
 *  $orders = \Corals\Modules\Marketplace\Models\Order::get();
 *
 * foreach ($orders as $order) {
 * $invoice = \Corals\Modules\Payment\Common\Models\Invoice::create([
 * 'code' => \Str::random(6),
 * 'currency' => $order->currency,
 * 'status' => 'paid',
 * 'invoicable_id' => $order->id,
 * 'invoicable_type' => get_class($order),
 * due_date' => $order->created_at,
 * 'sub_total' => $order->amount,
 * 'total' => $order->amount,
 * 'user_id' => $order->user->id,
 * 'created_at' => $order->created_at
 * ]);
 *
 * $invoice_items = [];
 * foreach ($order->items as $order_item) {
 * $invoice_items[] = [
 * 'code' => \Str::random(6),
 * 'description' => $order_item->description,
 * 'amount' => $order_item->amount,
 * 'itemable_id' => $order_item->id,
 * 'itemable_type' => get_class($order_item),
 * ];
 * }
 *
 * $invoice->items()->createMany($invoice_items);
 * }
 *
 *
 *
 *
 *
 *  $users = \Corals\User\Models\User::whereHas('roles', function ($role) {
 * $role->where('name', 'member');
 * })->get();
 *
 * foreach ($users as $user) {
 * if ($user->id % 25 == 0) {
 * for ($g = 1; $g <= 28; $g++) {
 * \Trexology\ReviewRateable\Models\Rating::create([
 * 'rating' => random_int(3, 5),
 * 'title' => 'Maecenas egestas arcu quis ligula',
 * 'body' => 'Nunc nonummy metus. Morbi nec metus. Aliquam eu nunc. Pellentesque ut neque. Cras dapibus.',
 * 'reviewrateable_id' => $g,
 * 'reviewrateable_type' => \Corals\Modules\Marketplace\Models\Product::class,
 * 'author_id' => $user->id,
 * 'author_type' => Corals\User\Models\User::class
 * ]);
 * }
 * }
 * }
 */
