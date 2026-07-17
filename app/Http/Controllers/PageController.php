<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function blog()
    {
        // Sample static blogs for real estate news and guide.
        $blogs = [
            [
                'title' => 'Top 10 Tips for First-Time Home Buyers in 2026',
                'excerpt' => 'Buying your first home can be a daunting process. Learn how to navigate the market and find your dream property with these expert tips.',
                'image' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=600&q=80',
                'date' => 'June 15, 2026',
                'author' => 'Homelist Team'
            ],
            [
                'title' => 'How to Value Your Property: A Complete Valuation Guide',
                'excerpt' => 'Are you planning to sell your house? Understanding the factors that determine property valuations is key to setting the right price.',
                'image' => 'https://images.unsplash.com/photo-1582407947304-fd86f028f716?auto=format&fit=crop&w=600&q=80',
                'date' => 'May 28, 2026',
                'author' => 'Ragini Ranawat'
            ],
            [
                'title' => 'Understanding Commercial Real Estate Investment Models',
                'excerpt' => 'Commercial properties offer attractive yields compared to residential listings. Find out how to get started in commercial investment today.',
                'image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=600&q=80',
                'date' => 'April 12, 2026',
                'author' => 'Sandeep Soni'
            ]
        ];

        return view('pages.blog', compact('blogs'));
    }
}
