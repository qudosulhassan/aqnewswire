@extends('layouts.admin')

@section('title', 'Newsletter Templates — CMS')
@section('page_title', 'Editorial Email Layouts & Templates')

@section('content')
<div class="space-y-6">

    <!-- Sub-Nav -->
    <div class="flex items-center space-x-3 border-b border-stone-200 pb-3 text-xs font-semibold">
        <a href="{{ route('admin.newsletters.index') }}" class="px-3 py-1.5 rounded hover:bg-stone-200 text-stone-600 transition">Subscribers</a>
        <a href="{{ route('admin.newsletters.campaigns') }}" class="px-3 py-1.5 rounded hover:bg-stone-200 text-stone-600 transition">Campaigns</a>
        <a href="{{ route('admin.newsletters.segments') }}" class="px-3 py-1.5 rounded hover:bg-stone-200 text-stone-600 transition">Segments</a>
        <a href="{{ route('admin.newsletters.templates') }}" class="px-3 py-1.5 rounded bg-stone-900 text-white font-bold">Templates</a>
        <a href="{{ route('admin.newsletters.analytics') }}" class="px-3 py-1.5 rounded hover:bg-stone-200 text-stone-600 transition">Analytics</a>
    </div>

    <!-- Create Template Form -->
    <div class="bg-white border border-stone-200 rounded p-6 shadow-xs">
        <h3 class="text-xs font-bold uppercase tracking-wider text-stone-900 mb-4">Add Email Template</h3>
        <form action="{{ route('admin.newsletters.templates.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Template Name *</label>
                    <input type="text" name="name" required placeholder="e.g. Daily Executive Morning Brief" class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Default Subject Line</label>
                    <input type="text" name="subject" placeholder="AQ NEWSWIRE Executive Briefing — [Date]" class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-stone-700 mb-1">HTML Template Boilerplate *</label>
                <textarea name="html_content" rows="4" required class="w-full px-3 py-2 border border-stone-300 rounded font-mono text-xs focus:outline-none focus:border-stone-900" placeholder="<div style='font-family: serif; max-width: 600px;'>...</div>"></textarea>
            </div>
            <div>
                <button type="submit" class="bg-stone-900 hover:bg-black text-white font-bold text-xs uppercase tracking-wider px-6 py-2.5 rounded transition">
                    Save Template
                </button>
            </div>
        </form>
    </div>

    <!-- Templates Table -->
    <div class="bg-white border border-stone-200 rounded shadow-xs overflow-hidden">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-stone-50 border-b border-stone-200 uppercase tracking-wider text-stone-600 font-bold">
                    <th class="p-4">Template</th>
                    <th class="p-4">Default Subject</th>
                    <th class="p-4">Created Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse($templates as $tmpl)
                    <tr class="hover:bg-stone-50 transition">
                        <td class="p-4 font-bold text-stone-900">
                            {{ $tmpl->name }}
                        </td>
                        <td class="p-4 text-stone-600">
                            {{ $tmpl->subject ?? '—' }}
                        </td>
                        <td class="p-4 text-stone-500">
                            {{ $tmpl->created_at->format('M d, Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="p-8 text-center text-stone-400">No custom templates created yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $templates->links() }}
    </div>

</div>
@endsection
