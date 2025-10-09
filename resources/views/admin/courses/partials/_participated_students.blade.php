<div class="mb-10 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
    <x-admin.locales :group="'Participated Users'" :count="$course->users->count()" />
    <div class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
        @if($course->users->count() > 0)
            <div class="course-users-list">
                @foreach($course->users as $user)
                    <div class="course-user-item">
                        <div class="course-user-avatar">
                            {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                        </div>
                        <div class="course-user-info">
                            <div class="course-user-name">
                                {{ $user->first_name }} {{ $user->last_name }}
                            </div>
                            <div class="course-user-email">
                                {{ $user->email }}
                            </div>
                        </div>
                        <div class="course-user-status">
                            <span class="course-user-status-badge {{ $user->pivot->status }}">
                                {{ ucfirst($user->pivot->status) }}
                            </span>
                            @if($user->pivot->enrolled_at)
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ \App\Helpers\Helper::convertDate($user->pivot->enrolled_at) }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="course-users-empty">
                <div class="course-users-empty-icon">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <p>No users enrolled yet</p>
            </div>
        @endif
    </div>

</div>
