<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Angle of Lines using HTML5 Canvas</title>

    <style>
        canvas {
            border: 1px solid black;
        }
    </style>
</head>

<body>
    <canvas id="main_canvas" width="500" height="500">
        Your browser does not support the canvas element.
    </canvas>
    <button onclick="start_line_cont();">Find Angle</button>

    <script>
        // create canvas element and append it to document body
        var canvas = document.getElementById('main_canvas');
        // Get canvas 2D context
        var ctx = canvas.getContext('2d');

        // Fill background of Canvas with White
        ctx.fillStyle = "White";
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = "Black";

        // Set initial variables
        let points = {};
        let p1_s = 0;
        let point_num = 0;
        let point_1_x = 0;
        let point_1_y = 0;
        let line_draw_cont = false;

        // Store last known position of mouse
        var pos = {
            x: 0,
            y: 0
        };

        // Find the angle created between two lines
        const find_two_lines_angle = (p1_s) => {
            // Find vector components
            var dAx = points[(p1_s + 1)][0] - points[p1_s][0];
            var dAy = points[(p1_s + 1)][1] - points[p1_s][1];
            var dBx = points[(p1_s + 2)][0] - points[(p1_s + 1)][0];
            var dBy = points[(p1_s + 2)][1] - points[(p1_s + 1)][1];
            var angle = Math.atan2(dAx * dBy - dAy * dBx, dAx * dBx + dAy * dBy);
            if (angle < 0) {
                angle = angle * -1;
            }
            var degree_angle = 180 - (angle * (180 / Math.PI));

            // Log result
            console.log('Angle between the last two lines is: ' + degree_angle);
        }

        // Find the angle of the last drawn line
        const find_line_angle = () => {
            let dx = pos.x - point_1_x;
            let dy = pos.y - point_1_y;
            let ang = (Math.atan2(dy, dx) * 180 / Math.PI) * -1;

            // Log result
            console.log('Angle of last line is: ' + ang);
        }

        // Set listening events
        document.addEventListener('mousedown', setPosition);
        document.addEventListener('mouseenter', setPosition);
        document.addEventListener('mousedown', draw_cont_line);

        // Get new position from mouse event
        function setPosition(e) {
            pos.x = original_x = e.clientX;
            pos.y = original_y = e.clientY;
        }

        // Check to see if line drawing is active
        const start_line_cont = (area) => {
            if (line_draw_cont == false) {
                line_draw_cont = true;
            } else line_draw_cont = false;
        }

        // Function to draw the line at the Canvas
        function draw_cont_line(e) {
            // Check for mouse left button must be pressed
            if (e.buttons !== 1) return;
            // Check for drawing to be active
            if (line_draw_cont !== true) return;

            console.log(line_draw_cont);

            ctx.beginPath(); // begin

            ctx.lineWidth = 3;
            ctx.lineCap = 'round';
            ctx.strokeStyle = '#c0392b';

            if (point_num == 0) {
                setPosition(e);
                points[point_num] = [pos.x, pos.y];
                str = JSON.stringify(points);
                //console.log(str);

                point_1_x = pos.x;
                point_1_y = pos.y;
                point_num++;

            } else {
                // Get mouse position
                setPosition(e);
                // Set point into array
                points[point_num] = [pos.x, pos.y];
                // Set From Spot
                ctx.moveTo(point_1_x, point_1_y);
                // Set To Spot
                ctx.lineTo(pos.x, pos.y);
                // Draw Line
                ctx.stroke();
                // Find angle of drawn line
                find_line_angle();

                // Set up for next point
                // Set latest mouse location (initial point) for next line
                point_1_x = pos.x;
                point_1_y = pos.y;

                // Check to see if two lines have been drawn, if yes then stop drawing and find angle created by the two past lines
                if (point_num == 2) {
                    console.log('End line');
                    line_draw_cont = false;
                    p1_s = point_num = 0;
                    // Find angle
                    find_two_lines_angle(p1_s);
                } else {
                    // Increase point number by 1
                    point_num++;
                }

            }
        }
    </script>
</body>

</html>