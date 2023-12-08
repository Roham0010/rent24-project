import { Step, Step1, Step2, Step3 } from "./";

export const Steps = ({
  step,
  setStep,
  formData,
  setFormData,
  error,
  submit,
}) => {
  return (
    <Step step={step} setStep={setStep} error={error} submit={submit}>
      {step === 1 ? (
        <Step1
          step={step}
          setStep={setStep}
          formData={formData}
          setFormData={setFormData}
          submit={submit}
          error={error}
        />
      ) : step === 2 ? (
        <Step2
          step={step}
          setStep={setStep}
          formData={formData}
          setFormData={setFormData}
          submit={submit}
          error={error}
        />
      ) : (
        <Step3
          step={step}
          setStep={setStep}
          formData={formData}
          setFormData={setFormData}
          submit={submit}
          error={error}
        />
      )}
    </Step>
  );
};
