/*
 * Drag a horizontal range over a graph overlay (div.graph).
 *
 * pnpRangeSelect(elements, onSelectEnd)
 *   elements:    elements to attach to (e.g. document.querySelectorAll('div.graph'))
 *   onSelectEnd: called as onSelectEnd(element, selection) when the button is
 *                released; selection = {x1, y1, x2, y2, width, height} in pixels
 *                relative to the element. The range always spans the full height.
 */
function pnpRangeSelect(elements, onSelectEnd) {
    Array.prototype.forEach.call(elements, function (el) {
        var box = null, startX = 0, dragged = false;

        function clamp(event) {
            var rect = el.getBoundingClientRect();
            return Math.max(0, Math.min(Math.round(event.clientX - rect.left), rect.width));
        }

        function draw(x) {
            var x1 = Math.min(startX, x), x2 = Math.max(startX, x);
            box.style.left = x1 + 'px';
            box.style.width = (x2 - x1) + 'px';
        }

        el.addEventListener('pointerdown', function (event) {
            if (event.button !== 0 || !event.isPrimary) {
                return;
            }
            event.preventDefault();
            el.setPointerCapture(event.pointerId);
            startX = clamp(event);
            box = document.createElement('div');
            box.className = 'pnp-range-select';
            el.appendChild(box);
            draw(startX);
        });

        el.addEventListener('pointermove', function (event) {
            if (box) {
                draw(clamp(event));
            }
        });

        function finish(event, cancelled) {
            if (!box) {
                return;
            }
            var x = clamp(event), done = box;
            box = null;
            done.style.opacity = 0;
            setTimeout(function () {
                el.removeChild(done);
            }, 500);
            if (cancelled) {
                return;
            }
            // a drag ends in a click; keep it from following a surrounding link
            if (x !== startX) {
                dragged = true;
                setTimeout(function () {
                    dragged = false;
                }, 100);
            }
            onSelectEnd(el, {
                x1: Math.min(startX, x),
                y1: 0,
                x2: Math.max(startX, x),
                y2: el.clientHeight,
                width: Math.abs(x - startX),
                height: el.clientHeight
            });
        }

        el.addEventListener('click', function (event) {
            if (dragged) {
                event.preventDefault();
                event.stopPropagation();
            }
        });

        el.addEventListener('pointerup', function (event) {
            finish(event, false);
        });
        el.addEventListener('pointercancel', function (event) {
            finish(event, true);
        });
    });
}
