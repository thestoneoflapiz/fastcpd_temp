<?php 
// dd($profession);
?>

<meta property="og:title" content="{{ $profession->title }}" />
<meta property="og:url" content="<?=URL::to("/courses/{$profession->url}")?>" />
<meta property="og:type" content="fastcpd_com">
<meta property="og:image" content="https://fastcpd.com/img/sample/poster-sample.png" />
<meta property="og:site_name" content="FastCPD">

@switch($profession->title)
    @case("Accountancy")
    <meta name = "description" content = "Learn audit methodologies, new taxation rules, and professional development from PRC accredited <profession> online CPD courses"> 
    <meta property="og:description" content="Learn audit methodologies, new taxation rules, and professional development from PRC accredited <profession> online CPD courses" />
    @break
    
    @case("Architecture")
    <meta name = "description" content = "Architecture online CPD courses about planning, design, managing cost and estimates, and project management.."> 
    <meta property="og:description" content="Architecture online CPD courses about planning, design, managing cost and estimates, and project management.." />
    @break
    
    @case("Civil Engineering")
    <meta name = "description" content = "Online CPD Courses from top firms about construction occupational safety, cost planning, quality management for civil engineers">
    <meta property="og:description" content="Online CPD Courses from top firms about construction occupational safety, cost planning, quality management for civil engineers" />
    @break

    @case("Medicine")
    <meta name = "description" content = "Learn online about basic life support, updates on studies, and nutrition while earning CPD units"> 
    <meta property="og:description" content="Learn online about basic life support, updates on studies, and nutrition while earning CPD units" />
    @break

    @case("Nursing")
    <meta name = "description" content = "Earn CPD units by watching courses on occupational safety, patient privacy, new concepts and trends on nursing">
    <meta property="og:description" content="Earn CPD units by watching courses on occupational safety, patient privacy, new concepts and trends on nursing" />
    @break

    @case("Professional Teachers")
    <meta name = "description" content = "Learn from online courses about school cultures, transofrmative education, and interactive teaching to get CPD units for PRC">
    <meta property="og:description" content="Learn from online courses about school cultures, transofrmative education, and interactive teaching to get CPD units for PRC" />
    @break

    @case("Pharmacy")
    <meta name = "description" content = "Learn about drug development, health outcomes, and pharmacy best practices with online CPD courses for pharmacists">  
    <meta property="og:description" content="Learn about drug development, health outcomes, and pharmacy best practices with online CPD courses for pharmacists" />
    @break

    @default
    <meta name = "description" content = "Browse online CPD courses for <profession>. Earn CPD units by watching videos on your own schedule."> 
    <meta property="og:description" content="Browse online CPD courses for <profession>. Earn CPD units by watching videos on your own schedule." />
    @break
@endswitch