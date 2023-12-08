export const Step2 = ({ formData, setFormData }) => {
  return [...Array(5).keys()].map((i) => (
    <div class="form-check">
      <input
        class="form-check-input"
        type="checkbox"
        name="checkbox"
        value={i}
        id={"flexCheckDefault" + i}
        key={i}
        checked={formData?.checks?.includes(i.toString()) ? "checked" : ""}
        onChange={(e) => {
          const { checked, value } = e.target;
          if (checked) {
            setFormData({
              ...formData,
              checks: [
                ...(formData?.checks ? formData?.checks : []),
                ...(formData?.checks?.includes(value) ? [] : [value]),
              ],
            });
          } else {
            setFormData({
              ...formData,
              checks: [
                ...(formData?.checks
                  ? formData?.checks.filter((i) => i !== value)
                  : []),
              ],
            });
          }
        }}
      />
      <label class="form-check-label" for={"flexCheckDefault" + i}>
        check {i}
      </label>
    </div>
  ));
};
