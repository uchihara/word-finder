require 'set'
require_relative 'word_finder/matrix'
require_relative 'word_finder/string_walker'
require_relative 'word_finder/dict'

class WordFinder
  def initialize fields, lengths, use_dict
    @matrix = Matrix.new fields
    @lengths = lengths
    @use_dict = use_dict
  end

  def find_words
    scanner = StringWalker.new @matrix
    results = Set.new
    (0...@matrix.x_max).each do |x|
      (0...@matrix.y_max).each do |y|
        @lengths.each do |length|
          hits = scanner.scan(length, x, y)
          results.merge(
            if @use_dict
              hits & Dict.new(length)
            else
              hits
            end
          )
        end
      end
    end
    results.sort.to_a
  end
end


