<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Hierarchy view component</title>

    <link rel="stylesheet" href="css/hierarchy-view.css">
    <link rel="stylesheet" href="css/main.css">
</head>

<body>

    <!--Basic style-->    
    <section class="basic-style">
        <a href="https://github.com/zikosichi/hierarchi-view" target="_blank" class="github-badge">
            <img src="images/github-red.svg">
        </a>
        <h1> 1. Basic Style</h1>
        <div class="hv-container">
            <div class="hv-wrapper">

                <!-- Key component -->
                <div class="hv-item">

                    <div class="hv-item-parent">
                        <p class="simple-card"> Parent </p>
                    </div>

                    <div class="hv-item-children">

                        <div class="hv-item-child">
                            <!-- Key component -->
                            <div class="hv-item">

                                <div class="hv-item-parent">
                                    <p class="simple-card"> Parent </p>
                                </div>

                                <div class="hv-item-children">

                                    <div class="hv-item-child">
                                        <p class="simple-card"> Child 1 </p>
                                    </div>


                                    <div class="hv-item-child">
                                        <p class="simple-card"> Child 2 </p>
                                    </div>

                                    <div class="hv-item-child">
                                        <p class="simple-card"> Child 2 </p>
                                    </div>

                                </div>

                            </div>
                        </div>


                        <div class="hv-item-child">
                            <p class="simple-card"> Child 2 </p>
                        </div>

                        <div class="hv-item-child">
                            <p class="simple-card"> Child 3 </p>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </section>

    <!--Management Hierarchy-->
    <section class="management-hierarchy">
        <a href="https://github.com/zikosichi/hierarchi-view" target="_blank" class="github-badge">
            <img src="images/github-blue.svg">
        </a>
        <h1> 1. Management Hierarchy</h1>
        <div class="hv-container">
            <div class="hv-wrapper">

                <!-- Key component -->
                <div class="hv-item">

                    <div class="hv-item-parent">
                        <div class="person">
                            <img src="https://pbs.twimg.com/profile_images/762654833455366144/QqQhkuK5.jpg" alt="">
                            <p class="name">
                                Ziko Sichi <b>/ CEO</b>
                            </p>
                        </div>
                    </div>

                    <div class="hv-item-children">

                        <div class="hv-item-child">
                            <!-- Key component -->
                            <div class="hv-item">

                                <div class="hv-item-parent">
                                    <div class="person">
                                        <img src="https://randomuser.me/api/portraits/women/50.jpg" alt="">
                                        <p class="name">
                                                 Wilner <b>/ Creative Director</b>
                                        </p>
                                    </div>
                                </div>

                                <div class="hv-item-children">

                                    <div class="hv-item-child">
                                        <div class="person">
                                            <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="">
                                            <p class="name">
                                                Anne Potts <b>/ UI Designer</b>
                                            </p>
                                        </div>
                                    </div>


                                    <div class="hv-item-child">
                                        <div class="person">
                                            <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                            <p class="name">
                                                Dan Butler <b>/ UI Designer</b>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="hv-item-child">
                                        <div class="person">
                                            <img src="https://randomuser.me/api/portraits/women/18.jpg" alt="">
                                            <p class="name">
                                                Mary Bower <b>/ UX Designer</b>
                                            </p>
                                        </div>
                                    </div>
                                    

                                </div>

                            </div>
                        </div>


                        <div class="hv-item-child">
                            <!-- Key component -->
                            <div class="hv-item">

                                <div class="hv-item-parent">
                                    <div class="person">
                                        <img src="https://randomuser.me/api/portraits/men/3.jpg" alt="">
                                        <p class="name">
                                            Gordon Clark <b>/ Senior Developer</b>
                                        </p>
                                    </div>
                                </div>

                                <div class="hv-item-children">

                                    <div class="hv-item-child">
                                        <div class="person">
                                            <img src="https://randomuser.me/api/portraits/men/41.jpg" alt="">
                                            <p class="name">
                                                Harry Bell <b>/ Front-end</b>
                                            </p>
                                        </div>
                                    </div>


                                    <div class="hv-item-child">
                                        <div class="person">
                                            <img src="https://randomuser.me/api/portraits/men/90.jpg" alt="">
                                            <p class="name">
                                                Matt Davies <b>/ Back-end</b>
                                            </p>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </section>

</body>

</html>