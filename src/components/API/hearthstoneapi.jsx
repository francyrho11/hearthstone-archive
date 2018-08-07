import React from "react";
import ReactDOM from "react-dom";
import axios from "axios";

class HearthstoneApi {
    constructor(props) {
        this.apiUrl = wp_options.apiUrl;
        this.apiKey = wp_options.apiKey;
        this.cardSets = {
            standard: {
                name: 'Anno del Corvo',
                sets: [
                    'Basic',
                    'Classic',
                    'The Witchwood',
                    'The Boomsday Project',
                    'Kobolds & Catacombs',
                    'Knights of the Frozen Throne',
                    'Journey to Un\'Goro'
                ]
            },
            wild: {
                name: 'Selvaggio',
                sets: [
                    'Blackrock Mountain',
                    'Goblins vs Gnomes',
                    'Mean Streets of Gadgetzan',
                    'Naxxramas',
                    'One Night in Karazhan',
                    'The Grand Tournament',
                    'The League of Explorers',
                    'Whispers of the Old Gods'
                ]
            }
        };
    }

    getCards() {
        return axios
            .get(this.apiUrl + 'cards', {
                headers: { 'X-Mashape-Key': this.apiKey },
                params: {
                    collectible: 1,
                    locale: 'itIT'
                }
            })
            .then(response => {
                let cards = Array();

                this.cardSets.standard.sets.forEach(set => {
                    cards = cards.concat(response.data[set]);
                });

                return cards;
            })
            .catch(error => console.log(error));
    }

}

export default HearthstoneApi;