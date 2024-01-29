<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .control{
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            background-color: #ffffff;
            width:250px;
            height:250px;
        }

        @keyframes progress {
            0% { --percentage: 0; }
            100% { --percentage: var(--value); }
        }

        @property --percentage {
            syntax: '<number>';
            inherits: true;
            initial-value: 0;
        }

        [role="progressbar"] {
            --percentage: var(--value);
            --primary: #369;
            --secondary: #adf;
            --size: 300px;
            animation: progress 2s 0.5s forwards;
            width: var(--size);
            aspect-ratio: 1;
            border-radius: 50%;
            position: relative;
            overflow: hidden;
            display: grid;
            place-items: center;
        }

        [role="progressbar"]::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: conic-gradient(var(--primary) calc(var(--percentage) * 1%), var(--secondary) 0);
            mask: radial-gradient(white 55%, transparent 0);
            mask-mode: alpha;
            -webkit-mask: radial-gradient(#0000 55%, #000 0);
            -webkit-mask-mode: alpha;
        }

        [role="progressbar"]::after {
            counter-reset: percentage var(--value);
            content: counter(percentage) '%';
            font-family: Helvetica, Arial, sans-serif;
            font-size: calc(var(--size) / 5);
            color: var(--primary);
        }

        /* demo */
        body {
            margin: 0;
            display: grid;
            place-items: center;
            height: 100vh;
            background: #f0f8ff;
        }
    </style>
</head>
<body>
    <div class="control">
        <form action="" method="post" id="form-upload">
            <input type="file" name="video" id="video">
            <button type="submit">send</button>
        </form>
    </div>

    <div role="progressbar" id="progress" aria-valuenow="67" aria-valuemin="0" aria-valuemax="100" 
    style="--value: 0"></div>
    <script>
        document.getElementById("form-upload").addEventListener("submit",function (e){
            e.preventDefault();

            const CHUNK_SIZE = 1000000; // 1MB
            let start = 0;
            let end = CHUNK_SIZE;
            let chunks = [];
            let file = document.getElementById('video').files[0];

            while (start < file.size) {
                let chunk = file.slice(start, end);
                chunks.push(chunk);
                start = end;
                end = start + CHUNK_SIZE;
            }

            let  i=0;
            chunks.forEach((chunk, index) => {
                let formData = new FormData();
                formData.append('file', chunk);
                formData.append('name', file.name);
                formData.append('index', index);

                fetch('upload.php', {
                    method: 'POST',
                    body: formData
                }).then(response => {
                    i++;
                    // Handle response
                    iter=(i/chunks.length) * 100
                    document.getElementById("progress").style="--value:"+iter
                }).catch(error => {
                    // Handle error
                });
            });
        })
    </script>
</body>
</html>