<?php
/**
 * $quizzes array
 */
?>

<div class="jumbotron">
    <h1 class="mt-5">QUIZ</h1>
    <?php if(!empty($quizzes)) { ?>
    <p class="lead">Please write your name and select a quiz.</p>
    <div class="alert alert-success hidden" id="message" role="alert"></div>
    <form id="quiz-form">
        <div class="form-group">
            <input type="text" class="form-control" id="name" placeholder="Enter your name">
        </div>
        <div class="form-group">
            <select class="form-control" id="quiz">
                <option value="">Select a quiz</option>
                <?php foreach($quizzes as $quiz) { ?>
                <option value="<?php echo $quiz['id'] ?>"><?php echo $quiz['name'] ?></option>
                <?php } ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Start</button>
    </form>
    <?php } else { ?>
        <p class="lead">No quiz found.</p>
    <?php } ?>
</div>