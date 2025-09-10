import { Provider } from "react-redux";
import { store } from "./store/store";
import AppRoutes from "./routes/AppRoutes";

function App() {
  return (
    <Provider store={store}>
      <div className="App">
        <AppRoutes />
      </div>
    </Provider>
  );
}

export default App;