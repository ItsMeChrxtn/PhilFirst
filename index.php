<?php
// Fetch content from CMS database for dynamic homepage
try {
    require_once 'backend/config.php';
    require_once 'backend/cms_helper.php';

    $cms = new CMSHelper($pdo);

    // Define default sections
    $sectionsToCheck = [
        'hero' => ['title' => 'Welcome to Philfirst', 'content' => 'Start your journey with a professional recruitment platform designed to connect you with real career opportunities.'],
        'why-apply' => ['title' => 'Why Apply Through Philfirst?', 'content' => 'We make job application simple, fast, and professional.'],
        'video-guide' => ['title' => 'How to Use Philfirst', 'content' => 'Watch this quick guide to learn how to apply and track your application.'],
        'video-guide-url' => ['title' => 'Video Guide URL', 'content' => 'your-video.mp4'],
        'cta-final' => ['title' => 'Your Career Starts Here', 'content' => 'Don\'t miss the opportunity. Apply now and take the first step toward your professional future.'],
        'feature-1' => ['title' => 'Fast Application', 'content' => 'Submit your application in minutes and get faster responses.'],
        'feature-2' => ['title' => 'Track Your Status', 'content' => 'Monitor your interview and application progress easily.'],
        'feature-3' => ['title' => 'Better Opportunities', 'content' => 'Connect with trusted employers and career growth opportunities.'],
        'footer' => ['title' => 'Footer', 'content' => '© 2026 Philfirst Recruitment Platform']
    ];

    // Fetch all sections and create if missing
    foreach($sectionsToCheck as $slug => $defaults) {
        $section = $cms->getPageBySlug($slug);
        if(!$section) {
            $cms->createPage($defaults['title'], $slug, $defaults['content'], 'published');
        }
    }

    // Now fetch again to get the data
    $hero = $cms->getPageBySlug('hero');
    $why_apply = $cms->getPageBySlug('why-apply');
    $video_guide = $cms->getPageBySlug('video-guide');
    $video_guide_url = $cms->getPageBySlug('video-guide-url');
    $cta = $cms->getPageBySlug('cta-final');
    $feature1 = $cms->getPageBySlug('feature-1');
    $feature2 = $cms->getPageBySlug('feature-2');
    $feature3 = $cms->getPageBySlug('feature-3');
    $footer_content = $cms->getPageBySlug('footer');

    // Use database content
    $hero_title = $hero['title'] ?? 'Welcome to Philfirst';
    $hero_subtitle = $hero['content'] ?? 'Start your journey with a professional recruitment platform designed to connect you with real career opportunities.';

    $why_title = $why_apply['title'] ?? 'Why Apply Through Philfirst?';
    $why_subtitle = $why_apply['content'] ?? 'We make job application simple, fast, and professional.';

    $video_title = $video_guide['title'] ?? 'How to Use Philfirst';
    $video_subtitle = $video_guide['content'] ?? 'Watch this quick guide to learn how to apply and track your application.';
    $video_url = $video_guide_url['content'] ?? 'your-video.mp4';

    $cta_title = $cta['title'] ?? 'Your Career Starts Here';
    $cta_text = $cta['content'] ?? 'Don\'t miss the opportunity. Apply now and take the first step toward your professional future.';

    $feature1_title = $feature1['title'] ?? 'Fast Application';
    $feature1_text = $feature1['content'] ?? 'Submit your application in minutes and get faster responses.';

    $feature2_title = $feature2['title'] ?? 'Track Your Status';
    $feature2_text = $feature2['content'] ?? 'Monitor your interview and application progress easily.';

    $feature3_title = $feature3['title'] ?? 'Better Opportunities';
    $feature3_text = $feature3['content'] ?? 'Connect with trusted employers and career growth opportunities.';

    $footer_text = $footer_content['content'] ?? '© 2026 Philfirst Recruitment Platform';

} catch(Exception $e) {
    // Fallback if database fails
    $hero_title = 'Welcome to Philfirst';
    $hero_subtitle = 'Start your journey with a professional recruitment platform designed to connect you with real career opportunities.';
    $why_title = 'Why Apply Through Philfirst?';
    $why_subtitle = 'We make job application simple, fast, and professional.';
    $video_title = 'How to Use Philfirst';
    $video_subtitle = 'Watch this quick guide to learn how to apply and track your application.';
    $video_url = 'your-video.mp4';
    $cta_title = 'Your Career Starts Here';
    $cta_text = 'Don\'t miss the opportunity. Apply now and take the first step toward your professional future.';
    $feature1_title = 'Fast Application';
    $feature1_text = 'Submit your application in minutes and get faster responses.';
    $feature2_title = 'Track Your Status';
    $feature2_text = 'Monitor your interview and application progress easily.';
    $feature3_title = 'Better Opportunities';
    $feature3_text = 'Connect with trusted employers and career growth opportunities.';
    $footer_text = '© 2026 Philfirst Recruitment Platform';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Philfirst | Start Your Career Today</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- AOS Animation -->
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

  <style>
    html { scroll-behavior: smooth; }

    .glow-btn {
      box-shadow: 0 0 0px rgba(16,185,129,0.7);
      transition: 0.3s ease;
    }

    .glow-btn:hover {
      box-shadow: 0 0 25px rgba(16,185,129,0.6);
      transform: translateY(-4px);
    }

    .floating {
      animation: float 4s ease-in-out infinite;
    }

    @keyframes float {
      0%,100% { transform: translateY(0px); }
      50% { transform: translateY(-15px); }
    }
  </style>
</head>

<body class="bg-gradient-to-br from-emerald-50 via-white to-emerald-100 text-gray-800">

<!-- ================= HERO ================= -->
<section class="min-h-screen flex items-center justify-center px-6 relative overflow-hidden">

  <!-- Animated Background Circles -->
  <div class="absolute top-0 left-0 w-96 h-96 bg-emerald-300 opacity-30 rounded-full blur-3xl floating"></div>
  <div class="absolute bottom-0 right-0 w-96 h-96 bg-emerald-400 opacity-30 rounded-full blur-3xl floating"></div>

  <div class="relative z-10 text-center max-w-4xl">

    <h1 class="text-5xl md:text-6xl font-bold mb-6" data-aos="fade-down">
      <?php echo htmlspecialchars($hero_title); ?>
    </h1>

    <p class="text-lg md:text-xl text-gray-600 mb-10" data-aos="fade-up" data-aos-delay="200">
      <?php echo htmlspecialchars($hero_subtitle); ?>
    </p>

    <div data-aos="zoom-in" data-aos-delay="400">
      <a href="frontend/"
         class="glow-btn bg-emerald-600 hover:bg-emerald-700 
                text-white font-semibold px-10 py-4 rounded-2xl 
                text-lg shadow-xl transition-all duration-300">
        🚀 Apply Now
      </a>
    </div>

  </div>
</section>


<!-- ================= WHY APPLY ================= -->
<section class="py-20 px-6 bg-white">
  <div class="max-w-6xl mx-auto text-center mb-16">
    <h2 class="text-4xl font-bold mb-4" data-aos="fade-up">
      <?php echo htmlspecialchars($why_title); ?>
    </h2>
    <p class="text-gray-600" data-aos="fade-up" data-aos-delay="200">
      <?php echo htmlspecialchars($why_subtitle); ?>
    </p>
  </div>

  <div class="grid md:grid-cols-3 gap-10 max-w-6xl mx-auto">

    <div class="bg-emerald-50 p-8 rounded-2xl shadow-lg hover:shadow-2xl transition"
         data-aos="fade-right">
      <div class="text-4xl mb-4">⚡</div>
      <h3 class="font-semibold text-xl mb-2"><?php echo htmlspecialchars($feature1_title); ?></h3>
      <p class="text-gray-600 text-sm">
        <?php echo htmlspecialchars($feature1_text); ?>
      </p>
    </div>

    <div class="bg-emerald-50 p-8 rounded-2xl shadow-lg hover:shadow-2xl transition"
         data-aos="fade-up">
      <div class="text-4xl mb-4">📈</div>
      <h3 class="font-semibold text-xl mb-2"><?php echo htmlspecialchars($feature2_title); ?></h3>
      <p class="text-gray-600 text-sm">
        <?php echo htmlspecialchars($feature2_text); ?>
      </p>
    </div>

    <div class="bg-emerald-50 p-8 rounded-2xl shadow-lg hover:shadow-2xl transition"
         data-aos="fade-left">
      <div class="text-4xl mb-4">🏆</div>
      <h3 class="font-semibold text-xl mb-2"><?php echo htmlspecialchars($feature3_title); ?></h3>
      <p class="text-gray-600 text-sm">
        <?php echo htmlspecialchars($feature3_text); ?>
      </p>
    </div>

  </div>
</section>


<!-- ================= VIDEO GUIDE ================= -->
<section class="py-20 px-6 bg-gradient-to-b from-white to-emerald-50">
  <div class="max-w-5xl mx-auto text-center">

    <h2 class="text-4xl font-bold mb-6" data-aos="fade-up">
      <?php echo htmlspecialchars($video_title); ?>
    </h2>

    <p class="text-gray-600 mb-10" data-aos="fade-up" data-aos-delay="200">
      <?php echo htmlspecialchars($video_subtitle); ?>
    </p>

    <div class="rounded-2xl overflow-hidden shadow-2xl border border-gray-200"
         data-aos="zoom-in">

      <!-- Video from CMS -->
      <video class="w-full" controls>
        <source src="<?php echo htmlspecialchars($video_url); ?>" type="video/mp4">
      </video>

    </div>

  </div>
</section>


<!-- ================= FINAL CTA ================= -->
<section class="py-20 px-6 text-center bg-emerald-600 text-white">

  <h2 class="text-4xl font-bold mb-6" data-aos="fade-up">
    <?php echo htmlspecialchars($cta_title); ?>
  </h2>

  <p class="mb-10 text-lg" data-aos="fade-up" data-aos-delay="200">
    <?php echo htmlspecialchars($cta_text); ?>
  </p>

  <div data-aos="zoom-in" data-aos-delay="400">
    <a href="frontend/"
       class="bg-white text-emerald-600 font-semibold 
              px-10 py-4 rounded-2xl text-lg shadow-xl 
              hover:bg-gray-100 transition">
      Get Started Now
    </a>
  </div>

</section>


<!-- FOOTER -->
<footer class="bg-white py-6 text-center text-gray-500 text-sm">
  <?php echo htmlspecialchars($footer_text); ?>
</footer>


<!-- AOS Script -->
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 1000,
    once: true
  });
</script>

<!-- Auto-Reload on CMS Updates -->
<script>
(function() {
  let lastUpdate = null;
  let isReloading = false;
  
  async function checkForUpdates() {
    if (isReloading) return;
    
    try {
      const response = await fetch('/backend/cms_updates.php');
      const data = await response.json();
      
      if (data.success) {
        const currentUpdate = data.last_update;
        
        if (lastUpdate === null) {
          lastUpdate = currentUpdate;
          return;
        }
        
        if (currentUpdate > lastUpdate) {
          isReloading = true;
          
          setTimeout(() => location.reload(), 1000);
        }
      }
    } catch (error) {
      console.error('Failed to check for updates:', error);
    }
  }
  
  checkForUpdates();
  setInterval(checkForUpdates, 5000);
})();
</script>

</body>
</html>
