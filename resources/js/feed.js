import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.data('curl', () => ({
    feeds: [],
    url: 'https://api.chucknorris.io/jokes/random',
    max: 10,
    init() {
        this.fetchFeed(this.feeds, this.alreadyPresent, this.url, 1000, this.max, this);
    },
    fetchFeed(feeds, alreadyPresent, url, timeout, max, self) {
        setTimeout(() => {
            fetch(url, {headers: {'cache-control': 'no-cache'}})
                .then(response => response.json())
                .then(data => {
                    if (feeds.length > max) {
                        feeds.shift();
                    }
                    if (!alreadyPresent(feeds, data)) {
                        feeds.push(data);
                    }
                })
                .catch(error => console.error(error))
            const self = this;
            self.fetchFeed(feeds, alreadyPresent, url, 30000, max, self);
        }, timeout, feeds, alreadyPresent, url, timeout, max, self);
    },
    alreadyPresent(haystack, needle) {
        for (let i in haystack) {
            if (i.id === needle.id) {
                return true
            }
        }
        return false;
    }
}));

Alpine.start();


window.addEventListener('DOMContentLoaded', function () {
    const placeholder = document.getElementById('feed');
})



