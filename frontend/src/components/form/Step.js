export const Step = ({ step, setStep, submit, error, children }) => {
  return (
    <div class="step step-1">
      <div className="card-header">
        Step {step}{" "}
        {error ? <small className="text-danger">({error})</small> : ""}
      </div>
      <div className="card-body">
        <div class="mb-3">{children}</div>
      </div>
      <div className="card-footer d-flex">
        {[2, 3].includes(step) && (
          <button
            type="button"
            className="btn btn-primary prev-step"
            onClick={() => setStep(step - 1)}
          >
            Previous
          </button>
        )}
        {3 === step && (
          <button
            type="submit"
            className="btn btn-success ms-auto"
            onClick={submit}
          >
            Submit
          </button>
        )}
        {[1, 2].includes(step) && (
          <button
            type="button"
            className="btn btn-primary ms-auto"
            onClick={() => setStep(step + 1)}
          >
            Next
          </button>
        )}
      </div>
    </div>
  );
};
