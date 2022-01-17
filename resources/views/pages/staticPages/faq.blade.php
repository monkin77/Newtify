@extends('layouts.app')

@section('content')
    

    <div class="container text-center my-5 p-5 bg-secondary" id="faqContainer">
        <h1>FAQ</h1>
        <p>
            Here, you can find answers to questions that you may have:
        </p>
        

        <div class="accordion text-center" id="faqAccordion">
            <div class="accordion-item my-5">
                <h4 class="accordion-header " id="headingOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        How do i make an article?
                    </button>
                </h4>
                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        On the top right corner of website there is a button which will redirect you to a page where you 
                        can create your article, by submitting a form with all information you want on it
                    </div>
                </div>
            </div>

            <div class="accordion-item my-5">
                <h4 class="accordion-header " id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Why is my feedback important?
                    </button>
                </h4>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        People's feed is filtered by the relevance of the articles, which is determined by the feedback it receives.
                        Thus, your feedback is essencial for the article to appear in others' feed! 
                    </div>
                </div>
            </div>

            <div class="accordion-item my-5">
                <h4 class="accordion-header " id="headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        What are Favorite Tags used for?
                    </button>
                </h4>
                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        Favorite Tags are used in order to filter your feed and show you articles in which you may have more interest,
                        articles that are about the Favorite Tags you choose. 
                    </div>
                </div>
            </div>

            <div class="accordion-item my-5">
                <h4 class="accordion-header " id="headingFour">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        What happens if I break the rules?
                    </button>
                </h4>
                <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        We expect people to follow rules in order to have a friendly environment. This way, if you break them, administrators
                        can report you and will get no access to your account 
                    </div>
                </div>
            </div>

            <div class="accordion-item my-5">
                <h4 class="accordion-header " id="headingFive">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                        What are the areas of expertise used for?
                    </button>
                </h4>
                <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        Areas of expertise of a person describes the influence the person has on a specific Tag. This way, the more 
                        articles you make about a tag and feedback you receive on those articles, the greater is your influence on that
                        matter, and your articles will be shown more frequently on the others' feed.
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
