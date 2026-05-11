{!! '<' . '?xml version="1.0" encoding="UTF-8"?>' !!}
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
    <title>{{ $siteName }}</title>
    <link>{{ $siteUrl }}</link>
    <description>Latest posts from {{ $siteName }}</description>
    <atom:link href="{{ $siteUrl }}/rss.xml" rel="self" type="application/rss+xml"/>
    @foreach($posts as $post)
    <item>
        <title>{{ htmlspecialchars($post->title) }}</title>
        <link>{{ rtrim(rtrim($siteUrl, '/') . $post->url, '/') . '/' }}</link>
        <guid>{{ rtrim(rtrim($siteUrl, '/') . $post->url, '/') . '/' }}</guid>
        <pubDate>{{ $post->published_at->toRfc2822String() }}</pubDate>
        <description>{{ htmlspecialchars($post->excerpt ?? '') }}</description>
        @if($post->author)<author>{{ $post->author->name }}</author>@endif
    </item>
    @endforeach
</channel>
</rss>
