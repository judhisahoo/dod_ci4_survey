<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMI Tool</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
    background: url('path_to_your_background_image.jpg') no-repeat center center fixed;
    background-size: cover;
    color: #fff;
    font-family: Arial, sans-serif; /* Use a clean font */
}

.container {
    background-color: rgba(0, 0, 0, 0.8); /* Darker semi-transparent background */
    padding: 40px; /* Add padding to the container */
    border-radius: 15px; /* More rounded corners */
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5); /* Add shadow for depth */
}

h1, h5 {
    text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7); /* More pronounced text shadow */
}

.lead {
    font-size: 1.25rem;
}

.btn {
    margin: 0 10px; /* Space between buttons */
}

        </style>
</head>
<body>
    <div class="container text-center my-5">
        <h1 class="display-4 fw-bold">CMI TOOL</h1>
        <p class="lead">Welcome to the CMI Portal</p>
        <p class="mb-4">This portal is designed to help identify and analyze skill gaps between employers' needs and learning institutions' curricula. Please select your role to proceed:</p>
        
        <div class="my-4">
            <h5 class="fw-semibold">Instructions:</h5>
            <ul class="list-unstyled">
                <li><strong>Employer Respondent:</strong> For employers who want to provide feedback on skill demands and the relevance of current job tasks.</li>
                <li><strong>Learning-Institution Respondent:</strong> For institutions who wish to assess how well their curricula align with the industry's needs.</li>
            </ul>
        </div>

        <div class="btn-group mt-4" role="group" aria-label="Feedback Options">
            <a href="<?php echo base_url();?>survey" class="btn btn-primary btn-lg">Employer Feedback</a>
            <a href="#" class="btn btn-secondary btn-lg">Institution Feedback</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
