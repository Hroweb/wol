<!-- ===== Aside Start ===== -->
<aside
    :class="sidebarToggle ? 'translate-x-0 lg:w-[90px]' : '-translate-x-full'"
    class="sidebar fixed left-0 top-0 z-9999 flex h-screen w-[290px] flex-col overflow-y-hidden border-r border-gray-200 bg-white px-5 border-gray-800 bg-black lg:static lg:translate-x-0"
>
    <!-- Aside Header -->
    <div
        :class="sidebarToggle ? 'justify-center' : 'justify-between'"
        class="flex items-center gap-2 pt-8 sidebar-header pb-7"
    >
        <a href="#">
            <x-application-logo />
        </a>
    </div>
    <!-- Aside Header -->

    <div
        class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar"
    >
        <!-- ===== Aside menu ===== -->
        <x-admin.aside-nav />
    </div>
</aside>
<!-- ===== Aside End ===== -->
