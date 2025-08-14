@component('mail::message')
# Hello {{ $user->name }},

Here are your quiz results:

- **Quiz Title:** {{ $quiz->title }}
- **Score:** {{ $attempt->score }} points
- **Date:** {{ $attempt->completed_at->format('d M, Y h:i A') }}

@component('mail::button', ['url' => 'https://your-app-link.com'])
View Leaderboard
@endcomponent

Thanks,  
{{ config('app.name') }}
@endcomponent
