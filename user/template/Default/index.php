<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <link rel="icon" href="../assets/favicon.ico"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>正在加载中</title>
    <script type="module" crossorigin src="./template/Default/assets/assets/index.7e8edd7c.js"></script>
    <link rel="stylesheet" href="./template/Default/assets/assets/index.247c2b73.css">
    <script type="module">var __vite_is_dynamic_import_support = false;</script>
    <script type="module">try {
            import("_").catch(() => 1);
        } catch (e) {
        }
        window.__vite_is_dynamic_import_support = true;</script>
    <script type="module">!function () {
            if (window.__vite_is_dynamic_import_support) return;
            console.warn("vite: loading legacy build because dynamic import is unsupported, syntax error above should be ignored");
            var e = document.getElementById("vite-legacy-polyfill"), n = document.createElement("script");
            n.src = e.src, n.onload = function () {
                System.import(document.getElementById('vite-legacy-entry').getAttribute('data-src'))
            }, document.body.appendChild(n)
        }();</script>
</head>
<body>
<div id="app"></div>

<script nomodule>!function () {
        var e = document, t = e.createElement("script");
        if (!("noModule" in t) && "onbeforeload" in t) {
            var n = !1;
            e.addEventListener("beforeload", (function (e) {
                if (e.target === t) n = !0; else if (!e.target.hasAttribute("nomodule") || !n) return;
                e.preventDefault()
            }), !0), t.type = "module", t.src = ".", e.head.appendChild(t), t.remove()
        }
    }();</script>
<script nomodule id="vite-legacy-polyfill" src="./template/Default/assets/assets/polyfills-legacy.a6202cdd.js"></script>
<script nomodule id="vite-legacy-entry"
        data-src="./template/Default/assets/assets/index-legacy.6abde332.js">System.import(document.getElementById('vite-legacy-entry').getAttribute('data-src'))</script>
<div style="text-align: center;margin-bottom: 2em"><?= $conf['statistics'] ?></div>
</body>
</html>
