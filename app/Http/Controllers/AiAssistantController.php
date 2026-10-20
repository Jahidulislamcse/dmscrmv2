<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Client, Meeting, ContentPost, Task};
use Illuminate\Support\Facades\{Auth, Http, Log};
use Carbon\Carbon;

class AiAssistantController extends Controller
{
    /**
     * AI Brief & Copy Generator Hub
     */
    public function index(Request $request)
    {
        $clients = Client::where('status', 'active')->orderBy('name')->get();
        $upcomingMeetings = Meeting::with('client')
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderBy('time')
            ->take(6)
            ->get();

        $upcomingPosts = ContentPost::with('client')
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->take(6)
            ->get();

        return view('ai.index', compact('clients', 'upcomingMeetings', 'upcomingPosts'));
    }

    /**
     * Generate Copy & Creative Brief
     */
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'platform' => 'required|string',
            'format' => 'required|string',
            'topic' => 'required|string|max:500',
            'tone' => 'required|string',
            'language' => 'required|string',
        ]);

        $client = $validated['client_id'] ? Client::find($validated['client_id']) : null;
        $clientName = $client ? $client->name : 'General Brand';
        $apiKey = config('services.anthropic.key');

        $result = null;

        // 1. If Anthropic Key is present, make live LLM API call
        if (!empty($apiKey)) {
            try {
                $prompt = "You are a senior creative agency copywriter and social media strategist based in Dhaka, Bangladesh working at DMS Software & Digital Agency.\n"
                    . "Generate 3 high-converting creative variations for the following brief:\n"
                    . "- Client / Brand: {$clientName}\n"
                    . "- Platform: {$validated['platform']}\n"
                    . "- Format: {$validated['format']}\n"
                    . "- Topic / Offer: {$validated['topic']}\n"
                    . "- Tone: {$validated['tone']}\n"
                    . "- Language: {$validated['language']} (If Bengali, output authentic Unicode Bangla)\n\n"
                    . "Format your response as structured JSON with keys:\n"
                    . "{\n"
                    . "  \"headline\": \"string\",\n"
                    . "  \"variations\": [\n"
                    . "    {\n"
                    . "      \"title\": \"Concept 1 / 2 / 3\",\n"
                    . "      \"hook\": \"Catchy opening line\",\n"
                    . "      \"body\": \"Engaging caption text with emojis\",\n"
                    . "      \"cta\": \"Call to action\",\n"
                    . "      \"hashtags\": [\"#Tag1\", \"#Tag2\"],\n"
                    . "      \"visual_direction\": \"Art direction note for graphic designer / editor\"\n"
                    . "    }\n"
                    . "  ],\n"
                    . "  \"designer_brief\": {\n"
                    . "    \"aspect_ratio\": \"1:1 Square or 9:16 Vertical\",\n"
                    . "    \"color_palette\": \"Primary brand colors\",\n"
                    . "    \"key_elements\": \"Main text overlay and focal point\"\n"
                    . "  }\n"
                    . "}";

                $response = Http::withHeaders([
                    'x-api-key' => $apiKey,
                    'anthropic-version' => '2023-06-01',
                    'content-type' => 'application/json',
                ])->timeout(20)->post('https://api.anthropic.com/v1/messages', [
                    'model' => config('services.anthropic.model', 'claude-sonnet-4-20250514'),
                    'max_tokens' => 1500,
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt]
                    ],
                ]);

                if ($response->successful()) {
                    $json = $response->json();
                    $content = $json['content'][0]['text'] ?? '';
                    // Extract JSON if wrapped in markdown code fence
                    if (preg_match('/\{[\s\S]*\}/', $content, $matches)) {
                        $result = json_decode($matches[0], true);
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Anthropic API Call failed, falling back to local agency prompt engine: ' . $e->getMessage());
            }
        }

        // 2. Fallback to Local Agency AI Rule Engine
        if (!$result) {
            $result = $this->generateLocalTemplates($clientName, $validated);
        }

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    /**
     * Local Creative Prompt & Copy Template Engine
     */
    private function generateLocalTemplates(string $clientName, array $params): array
    {
        $platform = $params['platform'];
        $format = $params['format'];
        $topic = $params['topic'];
        $lang = $params['language'];

        $isBangla = stripos($lang, 'Bengali') !== false || stripos($lang, 'Bangla') !== false;

        if ($isBangla) {
            return [
                'headline' => "{$clientName} — {$topic} (সোশ্যাল মিডিয়া ক্যাম্পেইন)",
                'variations' => [
                    [
                        'title' => 'কনসেপ্ট ১: ডিরেক্ট হুক ও অফার ফোকাস',
                        'hook' => "🔥 সেরা কোয়ালিটি আর স্পেশাল অফার এখন আপনার হাতের মুঠোয়!",
                        'body' => "আপনার দৈনন্দিন লাইফস্টাইলকে আরও প্রিমিয়াম করতে {$clientName} নিয়ে এলো আকর্ষণীয় কালেকশন। {$topic} নিয়ে এখনই অর্ডার করুন অথবা ভিজিট করুন আমাদের শোরুমে। লিমিটেড স্টক, দেরি না করে লুফে নিন!",
                        'cta' => '👉 এখনই ইনবক্স করুন অথবা লিংকে ক্লিক করে অর্ডার কনফার্ম করুন!',
                        'hashtags' => ["#{$clientName}", "#DhakaTrends", "#BestDealBD", "#BangladeshiBrand", "#OfferToday"],
                        'visual_direction' => 'বোল্ড টাইপোগ্রাফি সহ প্রোডাক্টের ৪K ক্লোজ-আপ শট। ব্যাকগ্রাউন্ডে ব্র্যান্ড কালার এবং ডিসকাউন্ট ব্যাজ হাইলাইট করতে হবে।',
                    ],
                    [
                        'title' => 'কনসেপ্ট ২: ইমোশনাল ও লাইফস্টাইল স্টোরিটেলিং',
                        'hook' => '✨ জীবনের প্রতিটি মুহূর্ত সাজুক নতুন আভিজাত্যে...',
                        'body' => "প্রতিটি দিন হোক উৎসবের মতো আনন্দময়। {$clientName}-এর নিখুঁত ফিনিশিং এবং বিশ্বমানের ডিজাইন আপনার ব্যক্তিত্বে যোগ করবে নতুন মাত্রা। {$topic} — আমাদের নতুন কালেকশন দেখতে আজই ঘুরে আসুন।",
                        'cta' => '🌟 কালেকশন দেখতে আজই ভিজিট করুন: dmsagency.com/shop',
                        'hashtags' => ["#{$clientName}", "#LifestyleBD", "#FestiveLook", "#PremiumQuality", "#BDStyle"],
                        'visual_direction' => 'মডেল সহ সিনেমাটিক ওয়ার্ম লাইটিং। টেক্সট কম রেখে ভিজ্যুয়াল নান্দনিকতা ফুটিয়ে তুলুন।',
                    ],
                    [
                        'title' => 'কনসেপ্ট ৩: রিলস / টিকটক শর্ট স্ক্রিপ্ট',
                        'hook' => '🎬 (ভিডিও শুরু) ৫ সেকেন্ডের মধ্যে দেখুন আমাদের সেরা কালেকশন!',
                        'body' => "সিন ১ (০-৩ সে): ফাস্ট ট্রানজিশন সহ প্রোডাক্ট রিভিল।\nসিন ২ (৩-৮ সে): ফিচারস ও কোয়ালিটি ক্লোজ-আপ ভিউ।\nসিন ৩ (৮-১৫ সে): এক্সক্লুসিভ অফার ডিল ও প্রাইস ড্রপ ব্যাজ।\nভয়েসওভার: 'আপনার পছন্দের সেরা ডিল পেতে এখনই যোগাযোগ করুন {$clientName}-এ!'",
                        'cta' => '📲 সেভ করুন এবং বন্ধুদের সাথে শেয়ার করুন!',
                        'hashtags' => ["#ReelsBD", "#ViralReels", "#TrendingBD", "#{$clientName}", "#ExplorePage"],
                        'visual_direction' => '৯:১৬ উল্লম্ব ফরম্যাট, দ্রুত গতিময় বিট কাট এবং স্ক্রিনে বড় বড় অ্যানিমেটেড সাবটাইটেল।',
                    ],
                ],
                'designer_brief' => [
                    'aspect_ratio' => $format === 'Reel / TikTok Script' ? '9:16 Vertical Video' : '1:1 Square (1080x1080)',
                    'color_palette' => 'ব্র্যান্ড প্রাইমারি কালার, গোল্ডেন ইয়েলো হাইলাইট এবং ডিপ স্লেট ব্যাকগ্রাউন্ড।',
                    'key_elements' => 'ক্লিন মার্জিন, মোবাইল-ফ্রেন্ডলি ফন্ট সাইজ এবং স্পষ্ট কল-টু-অ্যাকশন বোতাম।',
                ],
            ];
        }

        return [
            'headline' => "{$clientName} — {$topic} ({$platform} Campaign)",
            'variations' => [
                [
                    'title' => 'Concept 1: High-Conversion Hook & Urgent Offer',
                    'hook' => "🔥 Stop scrolling! The upgrade your lifestyle needed is finally here.",
                    'body' => "Elevate your everyday standard with {$clientName}. Experience premium craft, effortless utility, and an exclusive seasonal package for {$topic}. Designed for those who never compromise on quality.",
                    'cta' => '👉 Click Shop Now or message us directly to claim yours before stocks run out!',
                    'hashtags' => ["#{$clientName}", "#DhakaLifestyle", "#ExclusiveLaunch", "#DigitalAgencyBD", "#PremiumStandard"],
                    'visual_direction' => 'Ultra high-res hero product render on clean studio backdrop with accent glow and bold typography overlay.',
                ],
                [
                    'title' => 'Concept 2: Lifestyle Storytelling & Value-Driven',
                    'hook' => "✨ What if premium quality didn't come with friction?",
                    'body' => "At {$clientName}, every single detail is engineered to perfection. Whether you are stepping out for an executive meeting or refreshing your essentials, {$topic} delivers unmatched sophistication.",
                    'cta' => '🌟 Explore the full catalog at dmsagency.com or visit our nearest experience center.',
                    'hashtags' => ["#{$clientName}", "#EleganceRedefined", "#ModernLiving", "#AgencyCreatives", "#DhakaFinest"],
                    'visual_direction' => 'Candid lifestyle photography with warm cinematic color grade and subtle brand watermark.',
                ],
                [
                    'title' => 'Concept 3: 15s High-Retention Reel / TikTok Script',
                    'hook' => '🎬 [0-3s Visual Hook]: Fast snap transition from raw materials to final finished masterpiece.',
                    'body' => "Scene 1 (0-3s): Dynamic sound effect with rapid product reveal.\nScene 2 (4-8s): Key benefit in kinetic typography: 'Built for longevity. Styled for you.'\nScene 3 (9-15s): Model smile & packaging unbox.\nVoiceover: 'Upgrade to {$clientName}. Order today with same-day express dispatch.'",
                    'cta' => '📲 Tap the link in bio to order now!',
                    'hashtags' => ["#ReelsDaily", "#TrendingNow", "#{$clientName}", "#BehindTheScenes", "#AgencyProduction"],
                    'visual_direction' => '9:16 vertical 60fps export, beat-synced cuts with high-contrast animated subtitles.',
                ],
            ],
            'designer_brief' => [
                'aspect_ratio' => $format === 'Reel / TikTok Script' ? '9:16 Vertical Video (1080x1920)' : '1:1 Square (1080x1080)',
                'color_palette' => 'Signature brand palette with high-contrast CTA accents.',
                'key_elements' => 'Clear visual hierarchy, thumb-stopping contrast, and legible typography on mobile screens.',
            ],
        ];
    }

    /**
     * Export All Scheduled Meetings & Content Releases as Standard .ICS Calendar Feed
     */
    public function exportIcs()
    {
        $meetings = Meeting::with('client')
            ->where('date', '>=', now()->subDays(7)->toDateString())
            ->get();

        $posts = ContentPost::with('client')
            ->where('date', '>=', now()->subDays(7)->toDateString())
            ->get();

        $ics = "BEGIN:VCALENDAR\r\n";
        $ics .= "VERSION:2.0\r\n";
        $ics .= "PRODID:-//DMS Software & Digital Agency//CRM v2.0//EN\r\n";
        $ics .= "CALSCALE:GREGORIAN\r\n";
        $ics .= "METHOD:PUBLISH\r\n";
        $ics .= "X-WR-CALNAME:DMS Agency Production Calendar\r\n";
        $ics .= "X-WR-TIMEZONE:Asia/Dhaka\r\n";

        // Add Meetings
        foreach ($meetings as $m) {
            $dateStr = Carbon::parse($m->date)->format('Y-m-d');
            $timeStr = $m->time ? (strlen($m->time) === 5 ? $m->time . ':00' : $m->time) : '10:00:00';
            $dtStart = Carbon::parse($dateStr . ' ' . $timeStr)->format('Ymd\THis');
            $dtEnd = Carbon::parse($dateStr . ' ' . $timeStr)->addMinutes($m->duration ?: 60)->format('Ymd\THis');
            $client = $m->client->name ?? 'Client';
            $agenda = addcslashes($m->agenda ?: 'Client Meeting', ",;\\");

            $ics .= "BEGIN:VEVENT\r\n";
            $ics .= "UID:meeting-{$m->id}@dmsagency.com\r\n";
            $ics .= "DTSTAMP:" . gmdate('Ymd\THis\Z') . "\r\n";
            $ics .= "DTSTART:{$dtStart}\r\n";
            $ics .= "DTEND:{$dtEnd}\r\n";
            $ics .= "SUMMARY:[Meeting] {$client} - {$agenda}\r\n";
            $ics .= "LOCATION:" . addcslashes($m->location ?: 'DMS Conference Room / Google Meet', ",;\\") . "\r\n";
            $ics .= "DESCRIPTION:Client Strategy Session for {$client}. Status: " . ucfirst($m->status) . "\r\n";
            $ics .= "STATUS:CONFIRMED\r\n";
            $ics .= "END:VEVENT\r\n";
        }

        // Add Content Posts
        foreach ($posts as $p) {
            $postDateStr = Carbon::parse($p->date)->format('Y-m-d');
            $dtStart = Carbon::parse($postDateStr . ' 18:00:00')->format('Ymd\THis');
            $dtEnd = Carbon::parse($postDateStr . ' 19:00:00')->format('Ymd\THis');
            $client = $p->client->name ?? 'Client';
            $title = addcslashes($p->title ?: 'Content Release', ",;\\");

            $ics .= "BEGIN:VEVENT\r\n";
            $ics .= "UID:post-{$p->id}@dmsagency.com\r\n";
            $ics .= "DTSTAMP:" . gmdate('Ymd\THis\Z') . "\r\n";
            $ics .= "DTSTART:{$dtStart}\r\n";
            $ics .= "DTEND:{$dtEnd}\r\n";
            $ics .= "SUMMARY:[Post] {$client} - {$p->platform} ({$title})\r\n";
            $ics .= "DESCRIPTION:Scheduled " . ($p->post_type ?? 'deliverable') . " post on {$p->platform}. Caption: " . addcslashes(substr($p->caption ?? '', 0, 100), ",;\\") . "\r\n";
            $ics .= "STATUS:CONFIRMED\r\n";
            $ics .= "END:VEVENT\r\n";
        }

        $ics .= "END:VCALENDAR\r\n";

        return response($ics, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="dms_crm_production_schedule.ics"',
        ]);
    }
}
