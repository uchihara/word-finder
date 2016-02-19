require 'minitest/autorun'

require_relative '../../../lib/word_finder/cell'

describe Cell do
  let(:char) { 'x' }
  let(:obj) { Cell.new char }

  describe '#marked?' do
    it 'does not mark just after initialized' do
      obj.marked?.must_equal false
    end
  end

  describe '#mark' do
    it 'markes when mark!' do
      obj.mark!
      obj.marked?.must_equal true
    end
  end

  describe '#unmark!' do
    it 'unmakrs when unmark!' do
      obj.mark!
      obj.unmark!
      obj.marked?.must_equal false
    end
  end
end
