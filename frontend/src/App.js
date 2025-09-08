import { Provider } from "react-redux";
import { store } from "./store/store";
import AppRouter from "./routes/AppRouter";

function App() {
  return (
    <Provider store={store}>
      <div className="App">
        <AppRouter />
      </div>
    </Provider>
  );
}

export default App;