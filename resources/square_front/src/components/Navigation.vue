<template>
    <div class="relative h-full text-[14px] font-medium">
        <slot></slot>
        <nav>
            <ul class="flex flex-col gap-y-4">
                <li>
                    <NavigationLink linkTitle="Feed" href="/home" :active-primary-border-style="true">
                        <GridIcon color="currentColor" :page="page"></GridIcon>
                    </NavigationLink>
                </li>
                <li>
                    <NavigationLink href="/friends" linkTitle="Friends" :active-primary-border-style="true">
                        <FriendsIcon color="currentColor" :page="page"></FriendsIcon>
                    </NavigationLink>
                </li>
                <li>
                    <NavigationLink href="/photos" linkTitle="Photos" :active-primary-border-style="true">
                        <PhotoIcon color="currentColor" :page="page"></PhotoIcon>
                    </NavigationLink>
                </li>
                <li>
                    <NavigationLink linkTitle="Meet New People" href="/people" :active-primary-border-style="true">
                        <UserIcon color="currentColor" :page="page"></UserIcon>
                    </NavigationLink>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script setup>

    import {usePage} from "@inertiajs/vue3";
    const page = usePage();

    import NavigationLink from "./NavigationLink.vue";
    import FriendsIcon from "./icons/FriendsIcon.vue";
    import GridIcon from "./icons/GridIcon.vue";
    import PhotoIcon from "./icons/PhotoIcon.vue";
    import UserIcon from "./icons/UserIcon.vue";

</script>
