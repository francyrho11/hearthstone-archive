import React from "react";
import Card from "../UI/Card";

function Archive(props){

    var cards = [];

    if(props.cards){
        props.cards.forEach(card => {
            cards.push(<Card cardData={card} />);
        });
    }

    return (
        <div className="archive-cards">
            {cards}
        </div>
    )
}

export default Archive;