<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ILO Survey Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            color: #000;
            font-family: Arial, sans-serif;
            /* Use a clean font */
        }

        .container {
            background-color: rgba(255, 255, 255, 0.8);
            /* Darker semi-transparent background */
            padding: 40px;
            /* Add padding to the container */


        }

        .fb-bold {
            color: #003065;
            font-weight: bold;
            text-shadow: none;
            font-size: 32px;
        }

        .boxgrey {
            background: #F3F3F3;
            padding: 25px 40px 25px 40px;
            width: 70%;
            margin: 0 auto;
        }

        h1,
        h5 {
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
            /* More pronounced text shadow */
        }

        .fb-semibold {
            color: #000;
            text-shadow: none;
            font-size: 25px;
            font-weight: bold;
        }

        .lead {
            font-size: 1.25rem;
        }

        .list-unstyled {
            text-align: left;
            color: #222;
        }

        .list-unstyled li {
            line-height: 22px;
            margin-top: 15px;
        }

        .btn {
            margin: 0 10px;
            /* Space between buttons */
            background: #003065;
            font-size: 16px !important;
            border-radius: 10px;
        }
    </style>
</head>

<body>

    <div class="container-fluid" style="padding:0 !important;"><img src="<?php base_url(); ?>public/fe/images/cmi-tool-bg.webp" class="card-img-top" alt="Survey Banner"></div>
    <div class="container text-center my-5">
        <h1 class="display-4 fb-bold">Welcome to the CMI Portal</h1>

        <p class="mb-4">This portal is designed to help identify and analyze skill gaps between employers' needs and learning institutions' curricula. Please select your role to proceed:</p>

        <div class="my-4 boxgrey">
            <h5 class="fb-semibold">Instructions:</h5>
            <ul class="list-unstyled">
                <li><strong>Employer Respondent:</strong> For employers who want to provide feedback on skill demands and the relevance of current job tasks.</li>
                <li><strong>Learning-Institution Respondent:</strong> For institutions who wish to assess how well their curricula align with the industry's needs.</li>
            </ul>
        </div>

        <div class="btn-group mt-4" role="group" aria-label="Feedback Options">
            <a href="<?php echo base_url(); ?>survey" class="btn btn-primary btn-lg">Employer Feedback</a>
            <a href="#" class="btn btn-secondary btn-lg">Institution Feedback</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>