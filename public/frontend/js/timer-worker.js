let timerId = null;
let secondsRemaining = 0;

self.onmessage = function(e) {
    if (e.data.action === 'start') {
        secondsRemaining = e.data.seconds;
        if (timerId) clearInterval(timerId);
        
        timerId = setInterval(() => {
            secondsRemaining--;
            self.postMessage({
                action: 'tick',
                secondsRemaining: secondsRemaining
            });
            
            if (secondsRemaining <= 0) {
                clearInterval(timerId);
                self.postMessage({ action: 'finished' });
            }
        }, 1000);
    } else if (e.data.action === 'stop') {
        if (timerId) clearInterval(timerId);
    }
};
