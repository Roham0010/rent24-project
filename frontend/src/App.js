import "./App.css";
import { useState } from "react";
import { Steps, validateStep3Form } from "./components/form";

function App() {
  const [step, setStep] = useState(1);
  const [formData, setFormData] = useState({});
  const [error, setError] = useState("");

  const validate = () => {
    setTimeout(() => setError(""), 4000);
    if (!formData?.city) {
      setStep(1);
      setError("Fix the errors: city is not selected");
      return false;
    } else if (!formData?.checks || formData?.checks?.length <= 0) {
      setStep(2);
      setError("Fix the errors: check at least one item");
      return false;
    } else {
      const { error } = validateStep3Form(formData);
      if (error) {
        setStep(3);
        setError("Fix the errors: " + error);
        return false;
      }
    }

    return true;
  };
  const submit = (e) => {
    e.preventDefault();

    if (validate()) {
      console.log("###############/FORM_SUBMITTED/#################");
      console.log("FORM_DATA: ", formData);
      console.log("###############/FORM_SUBMITTED/#################");
      setFormData({});
    }
  };
  return (
    <div id="container" class="container mt-5">
      <div class="progress px-1" style={{ height: "3px" }}>
        <div
          class="progress-bar"
          role="progressbar"
          style={{ width: (step - 1) * 50 + "%" }}
          aria-valuenow="0"
          aria-valuemin="0"
          aria-valuemax="100"
        ></div>
      </div>
      <div class="step-container d-flex justify-content-between">
        <div class="step-circle" onClick={() => setStep(1)}>
          1
        </div>
        <div class="step-circle" onClick={() => setStep(2)}>
          2
        </div>
        <div class="step-circle" onClick={() => setStep(3)}>
          3
        </div>
      </div>

      <form id="multi-step-form">
        <div className="card">
          <Steps
            step={step}
            setStep={setStep}
            formData={formData}
            setFormData={setFormData}
            submit={submit}
            error={error}
            setError={setError}
          />
        </div>
      </form>
    </div>
  );
}

export default App;
