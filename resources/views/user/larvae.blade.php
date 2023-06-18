<x-user-layout>

    <main class="pt-8 pb-16 lg:pt-16 lg:pb-24 bg-white dark:bg-gray-900">
        <div class="flex justify-between px-4 mx-auto max-w-screen-xl ">
            <article class="mx-auto w-full max-w-3xl format format-sm sm:format-base lg:format-lg">
                <div class="text-xs 2xl:text-sm">
                    <div class="xl:flex items-start justify-between gap-x-16">
                        <div>
                            <h2 class="from-blue-400 to-purple-700 bg-gradient-to-r bg-clip-text text-transparent">Larvae Information</h2>
                            <p class="leading-7">
                                This is an immature life stage of an insect. But some people also use the term to describe the early
                                life stages of fish, frogs or other animals. Usually, the larva looks very different from the adult it
                                will become. A caterpillar, for example, doesn’t look much like a butterfly. The larval stage of the
                                insect may also have completely different organs and structures than the adult, as well as a different
                                diet. A frog larva has gills and breathes water, while the adult frog will come to the surface to fill
                                its lungs with air.
                            </p>
                            <p class="leading-7">
                                Larvae (the plural of larva) are often adapted to very different environments than they will live in
                                as adults. Adult mosquitoes are airborne, for instance. But their larvae hang out in small pockets of
                                still water. There they gobble up algae and bacteria living on the water’s surface
                            </p>
                        </div>
                        <img src="{{ asset('assets/images/larvae/header.jpg') }}" alt=""
                            class="hidden xl:block w-32 h-32 object-cover rounded-xl">
                    </div>

                    <h3>Key identifiers of larval mosquitoes</h3>
                    <ol class="list-inside list-disc">
                        <li>
                            Large head and thorax; narrow, wormlike abdomen.
                        </li>
                        <li>
                            Hang just below the water surface, breathing air through tubes at the end of the abdomen.
                        </li>
                        <li>
                            When disturbed, they wriggle or squirm downward with jerking movements.
                        </li>
                        <li>
                            Pupal stage is comma-shaped; also hangs just under the water surface.
                        </li>
                        <li>
                            Aquatic, usually in still or stagnant water, including swampy areas, puddles, gutters, and
                            discarded car tires.
                        </li>
                    </ol>
                    <h3>Life Cycle</h3>
                    <p class="leading-7">
                        After a blood meal, females rest a few days and develop 100-400 or more eggs. These they usually
                        deposit on the water, flying close and tapping the abdomen onto the surface. Eggs hatch in a few
                        days and spend about a week as “wrigglers.” The pupal stage lasts 2-3 days, after which adults
                        emerge, climbing out onto the water surface. Adults mate within a few days, and females begin
                        seeking blood. The life cycle usually takes a few weeks, but when conditions are right, it can
                        take only 10 days.
                    </p>
                    <section class="space-x-3 flex text-xs 2xl:text-sm">
                        <div class="flex flex-col items-center">
                            <img class="w-20 h-20 rounded object-cover order-1"
                                src="{{ asset('assets/images/larvae/larva1.webp') }}" alt="Large avatar">
                        </div>
                        <div class="flex flex-col items-center">
                            <img class="w-20 h-20 rounded object-cover order-1"
                                src="{{ asset('assets/images/larvae/larva2.webp') }}" alt="Large avatar">
                        </div>
                        <div class="flex flex-col items-center">
                            <img class="w-20 h-20 rounded object-cover order-1"
                                src="{{ asset('assets/images/larvae/mosquito.webp') }}" alt="Large avatar">
                        </div>
                    </section>
                </div>

                <p class="leading-6 text-xs 2xl:text-sm">
                    We have collected samples of larvae from different places and have analyzed them. The data is shown in the form of graphs and charts below.
                </p>

                <div class="text-xs 2xl:text-sm">
                    <h3>
                        Visualizations of Larvae Data
                    </h3>
                </div>
            </article>
        </div>
    </main>
</x-user-layout>
