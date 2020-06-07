$( document ).ready(function() {
    localStorage.clear();
    $( "#quiz-form" ).submit(function( event ) {
        let name,
            quiz;

        name = $( "#name" ).val();
        quiz = $( "#quiz option:selected" ).val();

        if (!name) {
            jQuery('body').showMessage('Please write your name!');
            event.preventDefault();
            return;
        }

        if (!quiz) {
            jQuery('body').showMessage('Please select a quiz!');
            event.preventDefault();
            return;
        }

        $.ajax({
            url: "user",
            dataType: "json",
            type: "post",
            contentType: 'application/json',
            data: JSON.stringify( { "name": name, "quiz": quiz } ),
            processData: false,
            success: function( response, textStatus, jQxhr ) {
                if (response.is_valid === false) {
                    jQuery('body').showMessage(response.messages[0]);
                } else {
                    localStorage.setItem('user', response.user['id']);
                    $.ajax({
                        url: "quiz/" + quiz + '/question',
                        type: "get",
                        processData: false,
                        success: function( response, textStatus, jQxhr ) {
                            $( "#content" ).html(jQuery('body').getContentHtml(response.question.question, response.question.answers, response.progressRate));
                            localStorage.setItem('quiz', quiz);
                            localStorage.setItem('question', response.question.id);
                        },
                        error: function( xhr, status, error ){
                            jQuery('body').showMessage(xhr.responseJSON.message);
                        }
                    });
                }
            },
            error: function( xhr, status, error ){
                jQuery('body').showMessage(xhr.responseJSON.message);
            }
        });

        event.preventDefault();
    });

    $( "#content" ).on( "click", "[id^=answer-]", function() {
        $('.question .row .col-md-6 .btn').removeClass('selectedAnswer');
        $(this).addClass('selectedAnswer');
        localStorage.setItem('answer', $(this).attr('rel'));
        event.preventDefault();
    });

    $( "#content" ).on( "click", "#next", function() {
        let user,
            quiz,
            question,
            answer;

        if (!localStorage.getItem('answer')) {
            alert("dsfs");
            jQuery('body').showMessage('Please select an answer!');
            return;
        }

        user = localStorage.getItem('user');
        quiz = localStorage.getItem('quiz');
        question = localStorage.getItem('question');
        answer = localStorage.getItem('answer');

        $.ajax({
            url: "quiz/answer",
            dataType: "json",
            type: "post",
            contentType: 'application/json',
            data: JSON.stringify( { "user": user, "quiz": quiz, "question": question, "answer": answer } ),
            processData: false,
            success: function( response, textStatus, jQxhr ) {
                if (response.is_valid === false) {
                    jQuery('body').showMessage(response.messages);
                } else {
                    $.ajax({
                        url: "quiz/" + quiz + '/question?current=' + question,
                        type: "get",
                        processData: false,
                        success: function( response, textStatus, jQxhr ) {
                            if (response.question) {
                                $( "#content" ).html(jQuery('body').getContentHtml(response.question.question, response.question.answers, response.progressRate));
                                localStorage.setItem('question', response.question.id);
                                localStorage.removeItem('answer');
                            } else {
                                $.ajax({
                                    url: "user/" + user + "/result/" + quiz,
                                    type: "get",
                                    processData: false,
                                    success: function( response, textStatus, jQxhr ) {
                                        $( "#content" ).html(jQuery('body').getResultHtml(response.name, response.total, response.correct));
                                        localStorage.clear();
                                    },
                                    error: function( xhr, status, error ){
                                        jQuery('body').showMessage(xhr.responseJSON.message);
                                    }
                                });
                            }
                        },
                        error: function( xhr, status, error ){
                            jQuery('body').showMessage(xhr.responseJSON.message);
                        }
                    });
                }
            },
            error: function( xhr, status, error ){
                jQuery('body').showMessage(xhr.responseJSON.message);
            }
        });

        event.preventDefault();
    });
});

jQuery.fn.extend({
    getContentHtml: function(question, answers, progressRate) {
        let questionHtml;

        questionHtml = '';
        questionHtml += '<div class="question">';
        questionHtml += '<h2>' + question + '</h2>';
        questionHtml += jQuery('body').getAnswerHtml(answers);
        questionHtml += '</div>';
        questionHtml += jQuery('body').getProgressBarHtml(progressRate);
        questionHtml += jQuery('body').getNextButton();

        return questionHtml;
    },
    getAnswerHtml: function(answers) {
        let answerHtml = '';
        $.each( answers, function( key, answer ) {
            if (key % 2 == 0) {
                answerHtml += '<div class="row row-bottom">';
            }
    
            answerHtml += '<div class="col-md-6">'
            answerHtml += '<button rel=' + answer.id + ' id="answer-' + answer.id + '" type="button" class="btn btn-primary btn-lg btn-block">' + answer.answer + '</button>';
            answerHtml += '</div>';
    
            if (key % 2 == 1) {
                answerHtml += '</div>';
            }
        });
    
        return answerHtml;
    },
    getProgressBarHtml: function(progressRate) {
        if (progressRate == 0) {
            return '';
        }
    
        let progressBarHtml;
        progressBarHtml = '<div class="progress">';
        progressBarHtml += '<div class="progress-bar progress-bar-striped bg-warning" role="progressbar" style="width: ' + progressRate + '%;" aria-valuenow="' + progressRate + '" aria-valuemin="0" aria-valuemax="100">';
        progressBarHtml += progressRate
        progressBarHtml += '%</div></div>';
    
        return progressBarHtml;
    },
    getNextButton: function() {
        return '<a class="btn btn-lg btn-primary" id="next" role="button">Next »</a>';
    },
    showMessage: function(message) {
        if ($("#message"). length) {
            $('#message').html(message).removeClass('hidden').addClass('show');
        } else {
            let html;
            html = '<div class="alert alert-success" id="message" role="alert">' + message + '</div>';
            $('h2').after(html);
        }

    },
    getResultHtml: function(name, total, corrects) {
        let html;
        html = '<div class="jumbotron">'
        html += '<h2>Thanks, ' + name + '!</h2>';
        html += '<h4>You responded correctly to ' + corrects + ' out of ' + total + ' questions.</h4>';
        html += '</div>';

        return html;
    }
});