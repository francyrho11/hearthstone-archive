import React from "react";
import ReactDOM from "react-dom";
import HearthstoneApi from "./components/API/HearthstoneApi";
import Archive from "./components/Archive/Archive";

// import style
import "./scss/base.scss";

class App extends React.Component {

  // default State object
  state = {
    contacts: []
  };

  constructor(props) {
    super(props);
    this.element = <h1>Hello, world!</h1>;
    this.apiUrl = wp_options.apiUrl;
    this.apiKey = wp_options.apiKey;
    this.hearthstoneApi = new HearthstoneApi();
  }

  componentDidMount() {
    const newCards = this.hearthstoneApi.getCards();

    newCards.then(
      response => {
        console.log(response);
        // create a new "State" object without mutating 
        // the original State object. 
        const newState = Object.assign({}, this.state, {
          cards: response
        });

        // store the new state object in the component's state
        this.setState(newState);
      });
  }

  render() {
    return (
      <Archive cards={this.state.cards} />
    );
  }
}

ReactDOM.render(<App />, document.getElementById("root"));